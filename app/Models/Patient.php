<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Models\MedicalRecord;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use CrudTrait;
    protected $fillable = [
        'medical_record_number',
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
