<?php

namespace App\Models;

use App\Models\MedicalRecord;
use App\Models\PrescriptionMedicine;
use Illuminate\Database\Eloquent\Model;

class PrescriptionRecord extends Model
{
    protected $fillable = [
        'medical_record_id',
        'payment_status'
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function prescriptionMedicines()
    {
        return $this->hasMany(PrescriptionMedicine::class);
    }
}
