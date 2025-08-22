<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Models\User;
use App\Models\MedicalRecord;
use Illuminate\Database\Eloquent\Model;

class PatientProfile extends Model
{
    use CrudTrait;
    protected $fillable = [
        'user_id',
        'full_name',
        'NIK',
        'birthdate',
        'gender',
        'address',
        'marriage_status',
        'phone',
        'BPJS_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
