<?php

namespace App\Services;

use App\Models\Locket;
use App\Events\DoctorIsFree;
use App\Models\CheckUpQueue;
use App\Models\DoctorProfile;

class QueueApp
{
    public function putInQueue($patient, $specialization): string
    {

        $locket = Locket::get()->random();
        $doctor = DoctorProfile::where('specialization_id', $specialization->id)
            ->get()->random();

        $isDoctorBusy = CheckUpQueue::where('doctor_profile_id', $doctor->id)->exists();

        $queue = CheckUpQueue::where('locket_id', $locket->id)->latest()->first();

        $checkUpQueue = CheckUpQueue::create([
            'patient_id' => $patient->id,
            'doctor_profile_id' => $doctor->id,
            'number' => $queue ? ++$queue->number : 1,
            'locket_id' => $locket->id
        ]);

        if (!$isDoctorBusy) {
            DoctorIsFree::dispatch($doctor);
        }

        return $checkUpQueue->number . $locket->code;
    }
}