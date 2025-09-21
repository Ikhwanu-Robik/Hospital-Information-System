<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PharmacyApp extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'pharmacyApp';
    }
}