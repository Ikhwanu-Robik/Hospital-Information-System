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

        $pharmacistRole = Role::create(['name' => 'pharmacist']);
        Permission::create(['name' => 'medicine.list']);
        Permission::create(['name' => 'medicine.show']);
        Permission::create(['name' => 'medicine.create']);
        Permission::create(['name' => 'medicine.update']);
        Permission::create(['name' => 'medicine.delete']);
        Permission::create(['name' => 'drug class.list']);
        Permission::create(['name' => 'drug class.show']);
        Permission::create(['name' => 'drug class.create']);
        Permission::create(['name' => 'drug class.update']);
        Permission::create(['name' => 'drug class.delete']);
        Permission::create(['name' => 'medicine form.list']);
        Permission::create(['name' => 'medicine form.show']);
        Permission::create(['name' => 'medicine form.create']);
        Permission::create(['name' => 'medicine form.update']);
        Permission::create(['name' => 'medicine form.delete']);
        Permission::create(['name' => 'medicine route.list']);
        Permission::create(['name' => 'medicine route.show']);
        Permission::create(['name' => 'medicine route.create']);
        Permission::create(['name' => 'medicine route.update']);
        Permission::create(['name' => 'medicine route.delete']);

        $pharmacistRole->givePermissionTo([
            'medicine.list',
            'medicine.show',
            'medicine.create',
            'medicine.update',
            'medicine.delete',
            'drug class.list',
            'drug class.show',
            'drug class.create',
            'drug class.update',
            'drug class.delete',
            'medicine form.list',
            'medicine form.show',
            'medicine form.create',
            'medicine form.update',
            'medicine form.delete',
            'medicine route.list',
            'medicine route.show',
            'medicine route.create',
            'medicine route.update',
            'medicine route.delete',
        ]);

        $doctorRole = Role::create(["name" => "doctor"]);
        $acceptPatientPermission = Permission::create(["name" => "accept patient"]);
        $prescribeMedicinePermission = Permission::create(["name" => "prescribe medicine"]);

        $doctorRole->givePermissionTo([
            $acceptPatientPermission,
            'medicine.list',
            'medicine.show',
            $prescribeMedicinePermission
        ]);

        $administrationOfficer = Role::create(['name' => 'administration_officer']);
        $directPatientPermission = Permission::create(['name' => 'direct patient']);
        $administrationOfficer->givePermissionTo([
            $directPatientPermission
        ]);
    }
}
