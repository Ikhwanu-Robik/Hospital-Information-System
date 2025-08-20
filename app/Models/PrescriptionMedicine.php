<?php

namespace App\Models;

use App\Models\Medicine;
use App\Models\PrescriptionRecord;
use Illuminate\Database\Eloquent\Model;

class PrescriptionMedicine extends Model
{
    protected $fillable = [
        'prescription_record_id',
        'medicine_id',
        'dose_amount',
        'frequency'
    ];

    public function prescriptionRecord()
    {
        return $this->belongsTo(PrescriptionRecord::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
