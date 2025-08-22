<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Models\DrugClass;
use App\Models\MedicineForm;
use App\Models\MedicineRoute;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use CrudTrait;
    protected $fillable = [
        'name',
        'generic_name',
        'drug_class_id',
        'medicine_form_id',
        'strength',
        'medicine_route_id',
        'unit',
        'stock',
        'price',
        'batch_number',
        'expiry_date',
        'manufacturer'
    ];

    public function drugClass()
    {
        return $this->belongsTo(DrugClass::class);
    }

    public function medicineForm()
    {
        return $this->belongsTo(MedicineForm::class);
    }

    public function medicineRoute()
    {
        return $this->belongsTo(MedicineRoute::class);
    }
}
