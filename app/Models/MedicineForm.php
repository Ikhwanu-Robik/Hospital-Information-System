<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Models\Medicine;
use Illuminate\Database\Eloquent\Model;

class MedicineForm extends Model
{
    use CrudTrait;
    protected $fillable = [
        'name',
    ];

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
}
