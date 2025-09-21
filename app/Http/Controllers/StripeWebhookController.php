<?php

namespace App\Http\Controllers;

use App\Events\StripePaymentProcessed;
use Stripe\Webhook;
use Illuminate\Http\Request;
use App\Facades\PharmacyApp;
use App\Http\Controllers\Controller;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $payload = $request->getContent();
        $event = null;
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                config('stripe.webhook_secret')
            );
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            return response('Webhook error while validating signature.', 401);
        }

        if ($event->type === 'checkout.session.completed') {
            $checkoutSession = $event->data->object;
            
            PharmacyApp::handlePaymentNews($checkoutSession);
            StripePaymentProcessed::dispatch($checkoutSession);
        }

        return response('OK', 200);
    }
}
