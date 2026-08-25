<?php

namespace App\Events;

use App\Facades\BPJS;
use App\Models\Patient;
use App\Models\DoctorProfile;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
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

        $bpjsPatient = BPJS::getPatient($this->patient->nik);
        $this->patient->BPJS = BPJS::validateMembership($bpjsPatient);
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
