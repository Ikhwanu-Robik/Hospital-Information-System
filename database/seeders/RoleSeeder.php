<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(["name" => "super admin"]);

        $doctorRole = Role::create(["name" => "doctor"]);
        $acceptPatientPermission = Permission::create(["name" => "accept patient"]);
        $prescribeDrugsPermission = Permission::create(["name" => "prescribe drugs"]);

        $doctorRole->givePermissionTo($acceptPatientPermission);
        $doctorRole->givePermissionTo($prescribeDrugsPermission);
    }
}
