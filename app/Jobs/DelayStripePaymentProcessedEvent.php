<?php

namespace App\Jobs;

use App\Events\StripePaymentProcessed;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class DelayStripePaymentProcessedEvent implements ShouldQueue
{
    use Queueable;

    private $checkoutSession;

    /**
     * Create a new job instance.
     */
    public function __construct($checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        StripePaymentProcessed::dispatch($this->checkoutSession);
    }
}
