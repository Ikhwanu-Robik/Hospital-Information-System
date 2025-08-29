<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class BPJS extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bpjs';
    }
}