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
    public function __construct($doctorProfileId, $patientId, $queueId)
    {
        logger('@PatientWishToMeetDoctor getting doctor profile');
        $doctorProfile = DoctorProfile::find($doctorProfileId);

        if ($doctorProfile) {
            logger('@PatientWishToMeetDoctor doctor profile with id ' . $doctorProfileId . ' not found');
        }

        logger('@PatientWishToMeetDoctor getting patient');
        $patient = Patient::find($patientId);

        if ($patient) {
            logger('@PatientWishToMeetDoctor patient with id ' . $patientId . ' not found');
        }

        $this->queueId = $queueId;
        $this->doctorProfile = $doctorProfile;
        $this->patient = $patient;

        try {
            logger('getting BPJS patient');
            $bpjsPatient = BPJS::getPatient($this->patient->nik);

            logger('validating BPJS patient membership');
            $this->patient->BPJS = BPJS::validateMembership($bpjsPatient);
            logger('validated BPJS patient membership');
        } catch (Exception $e) {
            logger('there was an error while getting BPJS patient and/or validating his membership');
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
