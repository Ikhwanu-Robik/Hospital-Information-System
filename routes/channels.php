<?php

use App\Enums\CheckUpStatus;
use App\Events\DoctorIsFree;
use App\Models\CheckUpQueue;
use App\Models\DoctorProfile;
use App\Models\DoctorSchedule;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('CheckUp.Doctors.{doctorProfile}', function ($user, DoctorProfile $doctorProfile) {
    // TODO: see if I can check the patient's permission
    $now = now();
    $isDoctorInSchedule = DoctorSchedule::where('doctor_profile_id', $doctorProfile->id)
        ->where('day_of_week', $now->dayName)
        ->where('start_time', '<=', $now->format('H:i:s'))
        ->where('end_time', '>=', $now->format('H:i:s'))
        ->exists();

    $isDoctorBusy = CheckUpQueue::where('doctor_profile_id', $doctorProfile->id)
            ->where('status', CheckUpStatus::WAITING->value)
            ->exists();

    if ($doctorProfile->user->can('accept patient') && $isDoctorInSchedule) {
        if ($isDoctorBusy) {
            DoctorIsFree::dispatch($doctorProfile); // ->delay(now()->addSeconds(3))
        }
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