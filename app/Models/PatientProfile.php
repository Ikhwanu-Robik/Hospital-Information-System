<?php

namespace App\Models;

use App\Models\MedicalRecord;
use Illuminate\Database\Eloquent\Model;

class PatientProfile extends Model
{
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

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
