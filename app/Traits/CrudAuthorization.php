<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait CrudAuthorization
{
    protected function superAdminAuth()
    {
        Log::info(backpack_user()->hasRole('super admin'));
        if (!backpack_user()->hasRole('super admin')) {
            CRUD::denyAccess(['list', 'show', 'create', 'update', 'delete']);
        }
    }
}
