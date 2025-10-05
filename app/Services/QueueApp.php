<?php

namespace App\Services;

use App\Models\Locket;
use App\Models\Setting;
use App\Enums\CheckUpStatus;
use App\Events\DoctorIsFree;
use App\Models\CheckUpQueue;
use App\Models\DoctorProfile;
use App\Models\DoctorOnlineStatus;

class QueueApp
{
    public $specialization;
    public $locket;
    public $doctor;
    public $previousQueue;

    public function putInQueue($patient, $specialization): string
    {
        $this->specialization = $specialization;

        $specializationHasQueue = CheckUpQueue::with('doctorProfile.specialization')
            ->get()->where('doctorProfile.specialization.id', $this->specialization->id)
            ->isNotEmpty();

        if ($specializationHasQueue) {
            // find number of queues owned by each locket that have queue
            $queueLockets = CheckUpQueue::selectRaw('locket_id, COUNT(locket_id) AS locket_count')
                ->whereHas('doctorProfile.specialization', function ($query) {
                    $query->where('id', $this->specialization->id);
                })
                ->with('locket')
                ->groupBy('locket_id')
                ->get();

            // if only some locket have queue
            if ($queueLockets->count() != Locket::count('id')) {
                // choose one of the missing lockets to use
                $usedLocketIds = $queueLockets->pluck('locket_id');
                $freeLockets = Locket::whereNotIn('id', $usedLocketIds)->get();
                $this->locket = $freeLockets->random();
                // note to self :
                // it is true that this locket is the only one present in the queue
                // if we only consider the chosen specialization, that is.
                // There might be another specialization with your random locket
                // so it is necessary to get the previous queue over here as well
                $this->previousQueue = CheckUpQueue::where('locket_id', $this->locket->id)->latest()->first();
            } else {
                // choose the locket with the least locket_count
                $this->locket = $queueLockets->sortBy('locket_count')->first()->locket;
                $this->previousQueue = CheckUpQueue::where('locket_id', $this->locket->id)->latest()->get()->first();
            }

            // find number of queues owned by each doctor that have queue
            $queueDoctors = CheckUpQueue::selectRaw('doctor_profile_id, COUNT(doctor_profile_id) AS queues_count')
                ->where('status', CheckUpStatus::WAITING->value)
                ->whereHas('doctorProfile.specialization', function ($query) {
                    $query->where('id', $this->specialization->id);
                })
                ->whereHas('doctorProfile.schedules', function ($query) {
                    $now = now();
                    $query->where('day_of_week', $now->dayName)
                        ->where('start_time', '<=', $now->format('H:i:s'))
                        ->where('end_time', '>=', $now->format('H:i:s'));
                })
                ->with('doctorProfile')
                ->groupBy('doctor_profile_id')
                ->get();

            $inScheduleDoctorCount = DoctorProfile::where('specialization_id', $this->specialization->id)
                ->whereHas('schedules', function ($query) {
                    $now = now();
                    $query->where('day_of_week', $now->dayName)
                        ->where('start_time', '<=', $now->format('H:i:s'))
                        ->where('end_time', '>=', $now->format('H:i:s'));
                })
                ->count('id');

            // if only some in-schedule doctor have queue
            if ($queueDoctors->count() != $inScheduleDoctorCount) {
                // choose one of the missing doctor to be given patient
                $busyDoctorIds = $queueDoctors->pluck('doctor_profile_id');
                $freeDoctorsInSchedule = DoctorProfile::where('specialization_id', $this->specialization->id)
                    ->whereNotIn('id', $busyDoctorIds)
                    ->whereHas('schedules', function ($query) {
                        $now = now();
                        $query->where('day_of_week', $now->dayName)
                            ->where('start_time', '<=', $now->format('H:i:s'))
                            ->where('end_time', '>=', $now->format('H:i:s'));
                    })
                    ->get();

                $this->doctor = $freeDoctorsInSchedule->random();
            } else {
                // choose the doctor with the least queues_count
                $this->doctor = $queueDoctors->sortBy('queues_count')->first()->doctorProfile;
            }
        } else {
            $this->locket = Locket::get()->random();
            $this->previousQueue = CheckUpQueue::where('locket_id', $this->locket->id)->latest()->first();
            $this->doctor = DoctorProfile::where('specialization_id', $specialization->id)
                ->whereHas('schedules', function ($query) {
                    $now = now();
                    $query->where('day_of_week', $now->dayName)
                        ->where('start_time', '<=', $now->format('H:i:s'))
                        ->where('end_time', '>=', $now->format('H:i:s'));
                })
                ->get()->random();
        }

        $isDoctorBusy = CheckUpQueue::where('doctor_profile_id', $this->doctor->id)
            ->where('status', CheckUpStatus::WAITING->value)
            ->exists();

        $doctorPingInterval = Setting::where('key', 'doctor-ping-interval')->first();
        if ($doctorPingInterval) {
            $isDoctorOnline = DoctorOnlineStatus::where('doctor_profile_id', $this->doctor->id)
                ->where('last_seen_at', '<', now()->addMilliseconds($doctorPingInterval->value * 2))
                ->exists();
        }

        $checkUpQueue = CheckUpQueue::create([
            'patient_id' => $patient->id,
            'doctor_profile_id' => $this->doctor->id,
            'number' => $this->previousQueue ? ++$this->previousQueue->number : 1,
            'locket_id' => $this->locket->id,
            'status' => CheckUpStatus::WAITING->value
        ]);

        if (!$isDoctorBusy && $isDoctorOnline) {
            DoctorIsFree::dispatch($this->doctor);
        }

        return $checkUpQueue->number . $this->locket->code;
    }
}