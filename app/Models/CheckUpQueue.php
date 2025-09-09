<?php

namespace App\Models;

use App\Models\Locket;
use App\Models\Patient;
use App\Models\DoctorProfile;
use Illuminate\Database\Eloquent\Model;

class CheckUpQueue extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_profile_id',
        'number',
        'locket_id'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctorProfile()
    {
        return $this->belongsTo(DoctorProfile::class);
    }

    public function locket()
    {
        return $this->belongsTo(Locket::class);
    }
}
