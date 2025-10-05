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

/**
 * There are two conditions where DoctorIsFree
 * is suitable for dispatching.
 * 
 * 1. When doctor does not have any queue with waiting patient,
 *    thus is free to accept a patient
 * 2. When doctor has a queue with waiting patient, but the doctor
 *    just finished checking up a patient, thus is free to accept
 *    another patient
 */
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
