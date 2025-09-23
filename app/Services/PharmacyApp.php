<?php

namespace App\Services;

use App\Facades\BPJS;
use App\Facades\Stripe;
use App\Enums\PaymentStatus;
use App\Models\Patient;
use Stripe\Checkout\Session;
use Stripe\Stripe as RealStripe;
use App\Models\PrescriptionRecord;
use App\Facades\Stripe as SelfMadeStripe;
use Stripe\Exception\InvalidRequestException;

class PharmacyApp
{
    private bool $patientHasActiveBPJS = false;

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

        try {
            if ($this->patientHasActiveBPJS) {
                $prescriptionRecord = PrescriptionRecord::with('prescriptionMedicines.medicine')
                    ->find($prescripionRecordId);
                $patient = Patient::find($prescriptionRecord->medicalRecord->patient_id);

                $invoice = Stripe::createInvoice($patient->stripe_customer_id, $lineItems);

                $this->reduceMedicinesStock($lineItems);

                $prescriptionRecord->payment_status = PaymentStatus::SUCCESSFUL->value;
                $prescriptionRecord->save();

                BPJS::setPatientNIK($patient->NIK)->sendInvoice($invoice);

                return $invoice;
            } else {
                $checkoutSessionParams = [
                    'line_items' => $lineItems,
                    'mode' => "payment",
                    'metadata' => [
                        'prescription_record_id' => $prescripionRecordId
                    ],
                    'success_url' => route('sell-medicine.success'),
                    'cancel_url' => route('sell-medicine.cancelled')
                ];

                $checkoutSession = Session::create($checkoutSessionParams);

                return $checkoutSession->url;
            }
        } catch (InvalidRequestException $e) {
            abort(400, $e->getMessage());
        }
    }

    public function withBPJS($patientHasActiveBPJS)
    {
        $this->patientHasActiveBPJS = $patientHasActiveBPJS;

        return $this;
    }

    public function handlePaymentNews($checkoutSession)
    {
        if ($checkoutSession->payment_status === "paid") {
            $prescriptionRecord = PrescriptionRecord::with('prescriptionMedicines.medicine')
                ->find($checkoutSession->metadata['prescription_record_id']);
            $prescriptionRecord->payment_status = PaymentStatus::SUCCESSFUL->value;
            $prescriptionRecord->save();

            $lineItems = SelfMadeStripe::getLineItems($checkoutSession->id);

            $arrLineItems = [];
            foreach ($lineItems as $lineItem) {
                $arrLineItem = [];
                $arrLineItem['price'] = $lineItem->price->id;
                $arrLineItem['quantity'] = $lineItem->quantity;

                array_push($arrLineItems, $arrLineItem);
            }

            $this->reduceMedicinesStock($arrLineItems);
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
            $priceId = $item['price'];
            $quantity = $item['quantity'];

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