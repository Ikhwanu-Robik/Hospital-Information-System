<?php

use App\Events\DoctorIsFree;
use App\Models\DoctorProfile;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('CheckUp.Doctors.{doctorProfile}', function ($user, DoctorProfile $doctorProfile) {
    // TODO: see if I can check the patient's permission
    if ($doctorProfile->user->can('accept patient')) {
        DoctorIsFree::dispatch($doctorProfile); // ->delay(now()->addSeconds(3))
        return true;
    }
    return false;
});

Broadcast::channel('Locket.{locket}', function ($user, $locket) {
    return true;
});
