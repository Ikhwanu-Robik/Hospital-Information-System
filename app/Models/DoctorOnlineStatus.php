<?php

namespace App\Models;

use App\Models\DoctorProfile;
use Illuminate\Database\Eloquent\Model;

class DoctorOnlineStatus extends Model
{
    protected $fillable = [
        'doctor_profile_id',
        'last_seen_at'
    ];

    public function doctorProfile()
    {
        return $this->belongsTo(DoctorProfile::class);
    }
}
