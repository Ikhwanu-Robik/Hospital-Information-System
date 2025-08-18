<?php

namespace App\Listeners;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Services\RoleProfileFactory;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Permission\Events\RoleAttached;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;

class CreateRoleProfile
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RoleAttached $event): void
    {
        $user = $event->model;
        $roles = $event->rolesOrIds;

        if ($roles instanceof Role) {
            RoleProfileFactory::createForUser($user, $roles->name);
        } else if (is_array($roles)) {
            $query = Role::query();
            foreach ($roles as $role) {
                $query->where('id', $role);
            }
            $eloquentRoles = $query->get();

            foreach ($eloquentRoles as $role) {
                RoleProfileFactory::createForUser($user, $role->name);
            }
        } else if ($roles instanceof Collection) {
            foreach ($roles as $role) {
                RoleProfileFactory::createForUser($user, $role->name);
            }
        }
    }
}
