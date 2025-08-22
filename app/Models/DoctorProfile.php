<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Models\User;
use App\Models\Specialization;
use Illuminate\Database\Eloquent\Model;

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
        'status',
        'schedule'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }
}
