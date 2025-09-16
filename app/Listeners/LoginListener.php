<?php

namespace App\Listeners;

use App\Models\DoctorOnlineStatus;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoginListener
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
    public function handle(Login $event): void
    {
        if ($event->user->doctorProfile) {
            DoctorOnlineStatus::firstOrCreate(
                [
                    'doctor_profile_id' => $event->user->doctorProfile->id
                ]
            );
        }
    }
}
