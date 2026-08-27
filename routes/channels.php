<?php

use App\Enums\CheckUpStatus;
use App\Events\DoctorIsFree;
use App\Models\CheckUpQueue;
use App\Models\DoctorProfile;
use App\Models\DoctorSchedule;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('CheckUp.Doctors.{doctorProfileId}', function ($user, $doctorProfileId) {
    logger('fetching the doctor profile by id');
    $doctorProfile = DoctorProfile::find($doctorProfileId);

    if (!$doctorProfile) {
        logger('the id doesn\'t correspond to any doctor profile y\'all');
        return false;
    }

    logger('now()');
    $now = now();

    logger('is doctor in schedule?');
    $isDoctorInSchedule = DoctorSchedule::where('doctor_profile_id', $doctorProfile->id)
        ->where('day_of_week', $now->dayName)
        ->where('start_time', '<=', $now->format('H:i:s'))
        ->where('end_time', '>=', $now->format('H:i:s'))
        ->exists();

    logger('does doctor have an awaiting patient?');
    $isDoctorBusy = CheckUpQueue::where('doctor_profile_id', $doctorProfile->id)
            ->where('status', CheckUpStatus::WAITING->value)
            ->exists();

    if ($doctorProfile->user->can('accept patient') && $isDoctorInSchedule) {
        logger('doctor can acccept patient, shocking. he\'s also in schedule too');
        if ($isDoctorBusy) {
            logger('he also has an awaiting patient');
            DoctorIsFree::dispatch($doctorProfileId);
            logger('dispatched DoctorIsFree event');
        }
        logger('okay, you may subscribe to this channel!');
        return true;
    }

    logger('the doctor doesn\'t have the permission to accept patient, or he\'s just not in schedule');
    return false;
});

Broadcast::channel('Locket.{locket}', function ($user, $locket) {
    return true;
});

Broadcast::channel('Medicine.Dispense.{prescriptionRecordId}', function () {
    return true;
});