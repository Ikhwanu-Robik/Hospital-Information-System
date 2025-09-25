<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Models\MedicalRecord;
use App\Models\PrescriptionMedicine;
use Illuminate\Database\Eloquent\Model;

class PrescriptionRecord extends Model
{
    use CrudTrait;
    protected $fillable = [
        'medical_record_id',
        'payment_status',
        'code'
    ];

    protected static function booted()
    {
        static::created(function ($prescription) {
            // Generate code AFTER we have an ID
            $prescription->code = sprintf(
                'RX-%s-%s-%s',
                $prescription->medicalRecord->patient_id,
                $prescription->medicalRecord->doctor_profile_id,
                $prescription->id
            );

            $prescription->save();
        });
    }

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function prescriptionMedicines()
    {
        return $this->hasMany(PrescriptionMedicine::class);
    }
}
