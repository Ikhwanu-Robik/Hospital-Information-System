<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stripe Secret Key
    |--------------------------------------------------------------------------
    |
    | This option is to be filled with the secret key obtainable from
    | Stripe's dashboard page.
    |
    */
    'secret_key' => env('STRIPE_SECRET_KEY', null),

    /*
    |--------------------------------------------------------------------------
    | Stripe Currency
    |--------------------------------------------------------------------------
    |
    | The currency to be used for Price object creation
    |
    */
    'currency' => env('STRIPE_CURRENCY', null),

    /*
    |--------------------------------------------------------------------------
    | Stripe Webhook Secret
    |--------------------------------------------------------------------------
    |
    | A string for verifying wheter this device is authorized to listen
    | to Events from Stripe's Webhook
    |
    */
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', null),
];