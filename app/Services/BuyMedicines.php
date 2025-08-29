<?php

namespace App\Services;

use Exception;
use App\Models\PrescriptionRecord;

class BuyMedicines
{
    protected PrescriptionRecord $prescriptionRecord;

    public function withPrescription($prescriptionRecord)
    {
        $this->prescriptionRecord = $prescriptionRecord;

        return $this;
    }

    public function buy()
    {
        // TODO: integrate with Stripe
    }

    public function reduceMedicineStock()
    {
        if (!$this->prescriptionRecord) {
            throw new Exception('no prescription record');
        }

        $prescriptionRecord = PrescriptionRecord::with('prescriptionMedicines.medicine')->find($this->prescriptionRecord->id);
        foreach ($prescriptionRecord->prescriptionMedicines as $prescriptionMedicine) {
            $prescriptionMedicine->medicine->stock -= $prescriptionMedicine->dose_amount;
            $prescriptionMedicine->medicine->save();
        }
    }
}