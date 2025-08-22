<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Models\Medicine;
use App\Models\PrescriptionRecord;
use Illuminate\Database\Eloquent\Model;

class PrescriptionMedicine extends Model
{
    use CrudTrait;

    protected $table = 'prescription_medicine';

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
