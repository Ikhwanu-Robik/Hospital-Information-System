<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacistProfile extends Model
{
    protected $fillable = [
        'user_id',
        'fullname',
        'license_number'
    ];
}
