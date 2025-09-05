<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\DoctorProfile;
use App\Models\Specialization;
use App\Events\PatientWishToMeetDoctor;

class CheckUp
{

    protected DoctorProfile $doctor;

    public function findDoctor(string $specializationName)
    {
        $specialization = Specialization::where('name', $specializationName)
            ->first();

        // get random doctor of the specified specialization
        $doctors = DoctorProfile::where('specialization_id', $specialization->id)->get();
        $randomIndex = rand(0, $doctors->count() - 1);
        $doctor = $doctors[$randomIndex];

        $this->doctor = $doctor;

        return $this;
        // in the future
        // PLAN: find doctor of the specified specialization with the least queuing patients
        // 1. create a table to contains all queing patients (doctor_profile_id, patient_id)
        // 2. get all queues of the doctor with the specified specialization
        // 3. count and group by doctor
        // 4. find the doctor wiith the least queue count
    }

    public function queueCheckUp(string $medical_record_number)
    {
        $patient = Patient::where("medical_record_number", $medical_record_number)->first();

        // TODO: insert into the still queue

        PatientWishToMeetDoctor::dispatch($this->doctor, $patient);

        return $this;
    }
}