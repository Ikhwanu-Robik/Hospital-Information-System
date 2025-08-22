<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PharmacistProfile extends Model
{
    use CrudTrait;
    protected $fillable = [
        'user_id',
        'full_name',
        'license_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
