<?php

namespace App\Models;

use App\Models\User;
use App\Models\DoctorSchedule;
use App\Models\Specialization;
use App\Models\DoctorOnlineStatus;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class DoctorProfile extends Model
{
    use CrudTrait;
    protected $fillable = [
        'user_id',
        'full_name',
        'license_number',
        'phone',
        'department',
        'specialization_id',
        'room_number',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function doctorOnlineStatus()
    {
        return $this->hasOne(DoctorOnlineStatus::class);
    }
}
