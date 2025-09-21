<?php

namespace App\Services;

use Stripe\Price;
use Stripe\Product;
use Stripe\StripeClient;

class Stripe
{
    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.secret_key'));
    }

    public function createProduct($name): Product
    {
        return $this->stripe->products->create([
            'name' => $name
        ]);
    }

    public function updateProduct($productId, $name): Product
    {
        return $this->stripe->products->update($productId, [
            'name' => $name
        ]);
    }

    public function deactivateProduct($productId): Product
    {
        return $this->stripe->products->update($productId, [
            'active' => false
        ]);
    }

    public function createPrice($productId, $price): Price
    {
        if (config('stripe.currency') == "IDR") {
            // for an Indonesian Rupiah currency, multiply by 100
            // because the last two zeros will be put after
            // decimal i.e. if the $price is 1000,
            // the stored value will be 10.00
            $price *= 100;
        }

        return $this->stripe->prices->create([
            'currency' => config('stripe.currency'),
            'unit_amount' => $price,
            'product' => $productId,
        ]);
    }

    public function deactivatePrice($priceId): Price
    {
        return $this->stripe->prices->update($priceId, [
            'active' => false
        ]);
    }

    public function getLineItems($checkoutSessionId) 
    {
        return $this->stripe->checkout->sessions->allLineItems($checkoutSessionId)->data;
    }
}