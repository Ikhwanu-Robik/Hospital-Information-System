<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Stripe\Invoice;

class BPJS
{
    private $NIK;

    /**
     * Check the BPJS membership of a citizen with the given NIK.
     * Returns true if the the citizen has an active BPJS membership.
     * Returns false if the citizen does not have a BPJS membership or if
     * the membership is no longer active.
     * 
     * @param mixed $NIK
     * @return bool
     */
    public function validatePatient($NIK)
    {
        $response = Http::withHeader('Authorization', "Cons-Id " . config('bpjs.cons_id'))
            ->post(config('bpjs.api_url') . '/bpjs/check', [
                'NIK' => $NIK
            ]);

        if ($response->failed()) {
            abort($response->getStatusCode(), $response->body());
        }

        $responseArr = $response->json();
        $isMember = $responseArr["member"];
        $isActive = false;
        if ($isMember) {
            $membershipExpirationDate = Carbon::createFromFormat("d-m-Y", $responseArr["active_until"]);
            $isActive = $membershipExpirationDate->greaterThan(now());
        }

        return $isMember && $isActive;
    }

    public function sendInvoice(Invoice $invoice)
    {
        $response = Http::withHeader('Authorization', "Cons-Id " . config('bpjs.cons_id'))
            ->post(config('bpjs.api_url') . '/bpjs/bill', [
                'NIK' => $this->NIK,
                'invoice_payment_link' => $invoice->hosted_invoice_url,
                'invoice_pdf_link' => $invoice->invoice_pdf
            ]);

        if ($response->failed()) {
            abort($response->getStatusCode(), $response->body());
        }
    }

    public function setPatientNIK($NIK)
    {
        $this->NIK = $NIK;

        return $this;
    }
}
