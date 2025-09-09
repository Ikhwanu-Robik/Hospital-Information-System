<?php

namespace App\Events;

use App\Models\CheckUpQueue;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class QueueReadyForBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $queueNumber;
    public $roomNumber;
    protected $locket;

    /**
     * Create a new event instance.
     */
    public function __construct(CheckUpQueue $checkUpQueue)
    {
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
            new PrivateChannel('Locket.' . $this->locket->id),
        ];
    }
}
