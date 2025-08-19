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
        $seeMedicinePermission = Permission::create(['name' => 'see medicine']);
        $insertMedicinePermission = Permission::create(['name' => 'insert medicine']);
        $editMedicinePermission = Permission::create(['name' => 'edit medicine']);
        $deleteMedicinePermission = Permission::create(['name' => 'delete medicine']);

        $pharmacistRole->givePermissionTo([
            $seeMedicinePermission,
            $insertMedicinePermission,
            $editMedicinePermission,
            $deleteMedicinePermission
        ]);

        $doctorRole = Role::create(["name" => "doctor"]);
        $acceptPatientPermission = Permission::create(["name" => "accept patient"]);
        $prescribeMedicinePermission = Permission::create(["name" => "prescribe medicine"]);

        $doctorRole->givePermissionTo([
            $acceptPatientPermission,
            $seeMedicinePermission,
            $prescribeMedicinePermission
        ]);

        $patientRole = Role::create(['name' => 'patient']);
        $queuePermission = Permission::create(['name' => 'queue']);
        $checkUpPermission = Permission::create(['name' => 'check up']);
        $dispensePrescriptionPermission = Permission::create(['name' => 'dispense prescription']);

        $patientRole->givePermissionTo([
            $queuePermission,
            $checkUpPermission,
            $dispensePrescriptionPermission
        ]);
    }
}
