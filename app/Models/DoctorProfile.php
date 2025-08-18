<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'specialization_id',
        'schedule'
    ];

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }
}
