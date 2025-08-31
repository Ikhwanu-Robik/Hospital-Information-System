<?php

namespace App\Observers;

use App\Models\Patient;

class PatientObserver
{
    /**
     * Handle the Patient "creating" event.
     */
    public function creating(Patient $patient): void
    {
        $patient->medical_record_number = "MRN-" . date('Y') . '-' . str_pad(Patient::max('id') + 1, 6, '0', STR_PAD_LEFT);
    }
}
