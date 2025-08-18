<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'license_number',
        'phone',
        'department',
        'specialization_id',
        'room_number',
        'status',
        'schedule'
    ];

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }
}
