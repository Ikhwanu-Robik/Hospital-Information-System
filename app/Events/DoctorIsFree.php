<?php

namespace App\Events;

use App\Models\DoctorProfile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DoctorIsFree
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $doctorProfile;
    public $doctorSpecialization;

    /**
     * Create a new event instance.
     */
    public function __construct(DoctorProfile $doctorProfile)
    {
        $this->doctorProfile = $doctorProfile;
        $this->doctorSpecialization = $doctorProfile->specialization->name;
    }
}
