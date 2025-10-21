<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class QZTray extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'qzTray';
    }
}