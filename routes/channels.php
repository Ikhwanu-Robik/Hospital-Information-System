<?php

use App\Models\DoctorProfile;
use App\Models\DoctorSchedule;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('CheckUp.Doctors.{doctorProfileId}', function ($user, DoctorProfile $doctorProfile) {
    $now = now();

    $isDoctorInSchedule = DoctorSchedule::where('doctor_profile_id', $doctorProfile->id)
        ->where('day_of_week', $now->dayName)
        ->where('start_time', '<=', $now->format('H:i:s'))
        ->where('end_time', '>=', $now->format('H:i:s'))
        ->exists();

    if ($doctorProfile->user->can('accept patient') && $isDoctorInSchedule) {
        return true;
    }

    return false;
});

Broadcast::channel('Locket.{locket}', function ($user, $locket) {
    return true;
});

Broadcast::channel('Medicine.Dispense.{prescriptionRecordId}', function () {
    return true;
});