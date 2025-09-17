<?php

namespace App\Events;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\DoctorProfile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PatientWishToMeetDoctor implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $queueId;
    public DoctorProfile $doctorProfile;
    public Patient $patient;

    /**
     * Create a new event instance.
     */
    public function __construct(DoctorProfile $doctorProfile, Patient $patient, $queueId)
    {
        $this->queueId = $queueId;
        $this->doctorProfile = $doctorProfile;
        $this->patient = $patient;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("CheckUp.Doctors.{$this->doctorProfile->id}"),
        ];
    }
}
