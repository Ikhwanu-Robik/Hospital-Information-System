<?php

namespace App\Models;

use App\Facades\Stripe;
use App\Models\MedicalRecord;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Patient extends Model
{
    use CrudTrait;

    protected static function booted()
    {
        static::creating(function ($patient) {
            $customer = Stripe::createCustomer($patient->medical_record_number);
            $patient->stripe_customer_id = $customer->id;
        });
    }

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
