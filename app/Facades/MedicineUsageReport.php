<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MedicineUsageReport extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'medicineUsageReport';
    }
}