<?php

namespace App\Events;

use App\Models\PrescriptionRecord;
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
    public function __construct(PrescriptionRecord $prescriptionRecord)
    {
        $this->paymentStatus = $prescriptionRecord->payment_status;
        $this->prescriptionRecordId = $prescriptionRecord->id;
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
