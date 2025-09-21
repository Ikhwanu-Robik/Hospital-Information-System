<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use Stripe\Checkout\Session;
use Stripe\Exception\InvalidRequestException;
use Stripe\Stripe as RealStripe;
use App\Models\PrescriptionRecord;
use App\Facades\Stripe as SelfMadeStripe;

class PharmacyApp
{
    /*
     *
     * array $lineItems = [
     *        [
     *           'price_id' => null,
     *           'quantity' => null
     *       ],
     *   ]
     *
     */
    public function buyMedicines(array $lineItems, $prescripionRecordId)
    {
        RealStripe::setApiKey(config('stripe.secret_key'));

        $checkoutSession = null;
        try {
            $checkoutSession = Session::create([
                'line_items' => $lineItems,
                'mode' => 'payment',
                'metadata' => [
                    'prescription_record_id' => $prescripionRecordId
                ],
                'success_url' => route('sell-medicine.success'),
                'cancel_url' => route('sell-medicine.cancelled')
            ]);
        } catch (InvalidRequestException $e) {
            abort(400, $e->getMessage());
        }

        return $checkoutSession->url;
    }

    public function handlePaymentNews($checkoutSession)
    {
        if ($checkoutSession->payment_status === "paid") { // || === "no_payment_required" when BPJS is used
            $prescriptionRecord = PrescriptionRecord::with('prescriptionMedicines.medicine')
                ->find($checkoutSession->metadata['prescription_record_id']);
            $prescriptionRecord->payment_status = PaymentStatus::SUCCESSFUL->value;
            $prescriptionRecord->save();

            $lineItems = SelfMadeStripe::getLineItems($checkoutSession->id);
            $this->reduceMedicinesStock($lineItems);
        } else if ($checkoutSession->payment_status === "unpaid") {
            $prescriptionRecord = PrescriptionRecord::with('prescriptionMedicines.medicine')
                ->find($checkoutSession->metadata['prescription_record_id']);
            $prescriptionRecord->payment_status = PaymentStatus::FAILED->value;
            $prescriptionRecord->save();
        }
    }

    public function reduceMedicinesStock($lineItems)
    {
        foreach ($lineItems as $item) {
            $priceId = $item->price->id;
            $quantity = $item->quantity;

            // Find medicine by stripe_price_id
            $medicine = \App\Models\Medicine::where('stripe_price_id', $priceId)->first();

            if ($medicine) {
                $oldStock = $medicine->stock;
                $newStock = $oldStock - $quantity;
                $medicine->updateQuietly(['stock' => $newStock]);
            }   
        }
    }
}