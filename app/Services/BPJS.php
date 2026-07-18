<?php

namespace App\Services;

use Carbon\Carbon;
use Stripe\Invoice;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class BPJS
{
    private $NIK;

    public function validateMembership(array $patient)
    {
        $isMember = $patient["member"];
        if (!$isMember) {
            return false;
        }
        $isActive = $this->isPatientMembershipActive($patient);
        return $isMember && $isActive;
    }

    private function isPatientMembershipActive(array $patient)
    {
        $membershipExpirationDate = Carbon::createFromFormat("d-m-Y", $patient["active_until"]);
        $isActive = $membershipExpirationDate->greaterThan(now());
        return $isActive;
    }

    public function getPatient(string $NIK)
    {
        $patient = $this->callBPJSAPI('/bpjs/check', ['NIK' => $NIK]);
        return $patient;
    }

    private function callBPJSAPI(string $path, array $data)
    {
        $response = $this->makeRequest($path, $data);
        return $this->handleResponse($response);
    }

    private function makeRequest(string $path, array $data)
    {
        $consId = "Cons-Id " . config('bpjs.cons_id');
        $url = config('bpjs.api_url') . $path;
        $response = Http::withHeader('Authorization', $consId)
            ->post($url, $data);
        return $response;
    }

    private function handleResponse(Response $response)
    {
        if ($response->failed()) {
            abort($response->getStatusCode(), $response->body());
        }
        return $response->json();
    }

    public function sendInvoice(Invoice $invoice)
    {
        if (!isset($this->NIK)) {
            $this->log("The NIK is not set", "sendInvoice");
            abort(500);
        }

        $this->callBPJSAPI('/bpjs/bill', [
            'NIK' => $this->NIK,
            'invoice_payment_link' => $invoice->hosted_invoice_url,
            'invoice_pdf_link' => $invoice->invoice_pdf
        ]);
    }

    public function setPatientNIK(string $NIK)
    {
        $this->NIK = $NIK;
        return $this;
    }

    private function log($message, $originFunction)
    {
        Log::info($message, [
            "class" => "App\\Services\\BPJS",
            "function" => $originFunction
        ]);
    }
}
