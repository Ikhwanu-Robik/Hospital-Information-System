<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use CrudTrait;
    protected $fillable = [
        'name'
    ];

    public function doctorProfiles()
    {
        return $this->hasMany(DoctorProfile::class);
    }
}
