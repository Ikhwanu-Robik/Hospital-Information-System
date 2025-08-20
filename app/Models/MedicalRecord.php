<?php

namespace App\Models;

use App\Models\DoctorProfile;
use App\Models\PatientProfile;
use App\Models\PrescriptionRecord;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $fillable = [
        'patient_profile_id',
        'doctor_profile_id',
        'complaint',
        'diagnosis'
    ];

    public function patientProfile()
    {
        return $this->belongsTo(PatientProfile::class);
    }

    public function doctorProfile()
    {
        return $this->belongsTo(DoctorProfile::class);
    }

    public function prescriptionRecord()
    {
        return $this->hasOne(PrescriptionRecord::class);
    }
}
