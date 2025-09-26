<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StripePaymentProcessed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $paymentStatus;
    protected $prescriptionRecordId;

    /**
     * Create a new event instance.
     */
    public function __construct($checkoutSession)
    {
        $this->paymentStatus = $checkoutSession->payment_status;
        $this->prescriptionRecordId = $checkoutSession->metadata['prescription_record_id'];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("Medicine.Dispense.{$this->prescriptionRecordId}"),
        ];
    }
}
