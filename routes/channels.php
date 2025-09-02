<?php

use App\Models\DoctorProfile;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('CheckUp.Doctors.{doctorProfile}', function ($user, DoctorProfile $doctorProfile) {
    // TODO: see if I can check the patient's permission
    return $doctorProfile->user->can('accept patient');
});
