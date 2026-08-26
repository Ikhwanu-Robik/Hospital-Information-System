<?php

namespace App\Events;

use App\Models\PrescriptionRecord;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class StripePaymentProcessed implements ShouldBroadcastNow
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
