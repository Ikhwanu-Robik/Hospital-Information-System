<?php

namespace App\Rules;

use App\Enums\PaymentStatus;
use App\Models\PrescriptionRecord;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PrescriptionIsNotPaid implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $prescriptionRecord = PrescriptionRecord::find($value);
        if ($prescriptionRecord->payment_status == PaymentStatus::SUCCESSFUL->value) {
            $fail("The selected prescription record must not already been paid");
        }
    }
}
