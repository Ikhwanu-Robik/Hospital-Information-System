<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class BuyMedicines extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'buyMedicines';
    }
}