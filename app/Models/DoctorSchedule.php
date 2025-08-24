<?php

namespace App\Models;

use App\Models\DoctorProfile;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class DoctorSchedule extends Model
{
    use CrudTrait;

    protected $fillable = [
        'doctor_profile_id',
        'day_of_week',
        'start_time',
        'end_time'
    ];

    public function doctorProfile()
    {
        return $this->belongsTo(DoctorProfile::class);
    }
}
