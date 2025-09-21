<?php

use App\Http\Controllers\StripeWebhookController;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Support\Facades\Route;

Route::post('/stripe-webhook', StripeWebhookController::class)->middleware(ForceJsonResponse::class);
