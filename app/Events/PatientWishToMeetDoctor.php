<?php

namespace App\Events;

use App\Models\Patient;
use App\Models\DoctorProfile;
use Exception;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Facades\BPJS;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class PatientWishToMeetDoctor implements ShouldBroadcastNow
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

        try {
            $bpjsPatient = BPJS::getPatient($this->patient->nik);
            $this->patient->BPJS = BPJS::validateMembership($bpjsPatient);
        } catch (Exception $e) {
            Logger('there was an error while getting BPJS patient and/or validating his membership');
            logger('error: ' . $e->getMessage());
        }
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
