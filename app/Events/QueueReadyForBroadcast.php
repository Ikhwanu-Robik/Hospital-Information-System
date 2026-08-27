<?php

namespace App\Events;

use App\Models\CheckUpQueue;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class QueueReadyForBroadcast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $queueNumber;
    public $roomNumber;
    protected $locket;

    /**
     * Create a new event instance.
     */
    public function __construct($checkUpQueueId)
    {
        logger('@QueueReadyForBroadcast getting check up queue of the given id');
        $checkUpQueue = CheckUpQueue::find($checkUpQueueId);

        if (!$checkUpQueue) {
            logger('@QueueReadyForBroadcast no check up queue for the given id');
        }

        $this->locket = $checkUpQueue->locket;
        $this->queueNumber = $checkUpQueue->number . $checkUpQueue->locket->code;
        $this->roomNumber = $checkUpQueue->doctorProfile->room_number;
        $this->message = "Number " . $this->queueNumber . " may meet the doctor at room " . $checkUpQueue->doctorProfile->room_number;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('Locket.' . $this->locket->id),
        ];
    }
}
