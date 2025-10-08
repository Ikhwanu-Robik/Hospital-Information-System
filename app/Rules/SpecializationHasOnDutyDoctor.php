<?php

namespace App\Rules;

use Closure;
use App\Models\DoctorProfile;
use App\Models\Specialization;
use Illuminate\Contracts\Validation\ValidationRule;

class SpecializationHasOnDutyDoctor implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $specialization = Specialization::where('name', $value)->first();
        $specializationHasOnDutyDoctor = DoctorProfile::where('specialization_id', $specialization->id)
            ->whereHas('schedules', function ($query) {
                $now = now();
                $query->where('day_of_week', $now->dayName)
                    ->where('start_time', '<=', $now->format('H:i:s'))
                    ->where('end_time', '>=', $now->format('H:i:s'));
            })->exists();

        if (!$specializationHasOnDutyDoctor) {
            $fail("There is no $specialization->name doctor on duty right now");
        }
    }
}
