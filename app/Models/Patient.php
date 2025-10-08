<?php

namespace App\Models;

use App\Models\MedicalRecord;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

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
        'stripe_customer_id'
    ];

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
