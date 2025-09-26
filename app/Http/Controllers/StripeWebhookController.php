<?php

namespace App\Http\Controllers;

use Stripe\Webhook;
use App\Facades\PharmacyApp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\DelayStripePaymentProcessedEvent;
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
            DelayStripePaymentProcessedEvent::dispatch($checkoutSession)->delay(3);
        }

        return response('OK', 200);
    }
}
