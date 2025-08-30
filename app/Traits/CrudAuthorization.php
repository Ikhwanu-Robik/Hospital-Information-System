<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait CrudAuthorization
{
    protected function superAdminAuth()
    {
        if (!backpack_user()->hasRole('super admin')) {
            CRUD::denyAccess(['list', 'show', 'create', 'update', 'delete']);
        }
    }

    /**
     * Determine whether the currently logged in backpack user
     * has the permission to perform an operation on the
     * current resource.
     * 
     * The permission name is assumed to follow the convention
     * of pattern "resource.operation".
    */
    protected function determineResourcePermission()
    {
        $user = backpack_user();
        $resource = $this->crud->entity_name;
        $operation = $this->crud->getCurrentOperation();
        $permission = $resource . '.' . $operation;

        Log::info($permission);

        if ($user->cannot($permission)) {
            CRUD::denyAccess($operation);
        }
    }

    protected function administrationAuth()
    {
        if (backpack_user()->cannot('direct patient')) {
            CRUD::denyAccess(['list', 'show', 'create', 'update', 'delete']);
        }
        else if (backpack_user()->can('direct patient')) {
            CRUD::denyAccess(['delete']);
            CRUD::allowAccess(['list', 'show', 'create', 'update']);
        }
    }
}
