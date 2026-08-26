<?php

use App\Http\Controllers\QZTrayPrintingController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Support\Facades\Route;

Route::post('/stripe-webhook', StripeWebhookController::class)->middleware(ForceJsonResponse::class);

Route::get('/qztray/certificate', [QZTrayPrintingController::class, 'certificate'])->middleware(ForceJsonResponse::class);
Route::post('/qztray/message/sign', [QZTrayPrintingController::class, 'sign'])->middleware(ForceJsonResponse::class);

Route::get('/medicine-dispense-status/{prescriptionRecord}', function (\Illuminate\Http\Request $request, \App\Models\PrescriptionRecord $prescriptionRecord) {
    \App\Events\StripePaymentProcessed::dispatch($prescriptionRecord);
});