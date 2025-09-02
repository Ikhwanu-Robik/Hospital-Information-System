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

    public DoctorProfile $doctorProfile;
    public Patient $patient;
    public array $medicalRecords;

    /**
     * Create a new event instance.
     */
    public function __construct(DoctorProfile $doctorProfile, Patient $patient)
    {
        $this->doctorProfile = $doctorProfile;
        $this->patient = $patient;
        $this->medicalRecords = MedicalRecord::with('prescriptionRecord.prescriptionMedicines.medicine', 'doctorProfile.specialization')
            ->where('patient_id', $patient->id)
            ->get()->toArray();
        // the eager loaded relationships will be missing without toArray()
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
