<?php

namespace App\Listeners;

use App\Events\DoctorIsFree;
use App\Events\PatientWishToMeetDoctor;
use App\Models\CheckUpQueue;
use App\Events\QueueReadyForBroadcast;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DoctorIsFreeListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DoctorIsFree $event): void
    {
        $oldestQueue = CheckUpQueue::oldest('created_at')
            ->with(['doctorProfile', 'patient'])
            ->where('doctor_profile_id', $event->doctorProfile->id)
            ->first();

        if ($oldestQueue) {
            QueueReadyForBroadcast::dispatch($oldestQueue);
            PatientWishToMeetDoctor::dispatch($oldestQueue->doctorProfile, $oldestQueue->patient, $oldestQueue->id);
        }
    }
}
