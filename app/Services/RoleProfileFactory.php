<?php

namespace App\Services;

class RoleProfileFactory
{
    public static function createForUser($user, $roleName)
    {
        $map = [
            'doctor' => \App\Models\DoctorProfile::class,
        ];

        if (isset($map[$roleName])) {
            $profileClass = $map[$roleName];
            $relationName = lcfirst(class_basename($profileClass));

            if (!$user->$relationName) {
                $user->$relationName()->create();
            }
        }
    }
}