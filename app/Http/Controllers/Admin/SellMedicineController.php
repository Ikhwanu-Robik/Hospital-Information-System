<?php

namespace App\Http\Controllers\Admin;

use App\Facades\PharmacyApp;
use App\Models\PrescriptionRecord;
use App\Http\Requests\BuyMedicineRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;

class SellMedicineController extends CrudController
{
    public function index()
    {
        // return a Backpack-style view
        return view('admin.sell-medicine');
    }

    public function searchMedicines(BuyMedicineRequest $buyMedicineRequest)
    {
        $prescription = PrescriptionRecord::find($buyMedicineRequest->validated('id'));
        $prescriptionMedicines = PrescriptionRecord::with(['prescriptionMedicines.medicine'])
            ->find($buyMedicineRequest->validated('id'))->prescriptionMedicines;

        return view('admin.sell-medicine', ['prescription' => $prescription, 'prescriptionMedicines' => $prescriptionMedicines]);
    }

    public function buyMedicines(BuyMedicineRequest $buyMedicineRequest)
    {
        session(['prescription_record_id' => $buyMedicineRequest->validated('id')]);

        $prescriptionRecord = PrescriptionRecord::with('prescriptionMedicines.medicine')->find($buyMedicineRequest->validated('id'));
        $medicalRecord = PrescriptionRecord::with('medicalRecord.patient')
            ->find($buyMedicineRequest->validated('id'))->medicalRecord;

        // if (!BPJS::validatePatient($medicalRecord->patient->BPJS_number)) {
        // TODO: integrate with BPJS

        $lineItems = $prescriptionRecord->prescriptionMedicines->map(fn($pm) => [
            'price' => $pm->medicine->stripe_price_id,
            'quantity' => $pm->dose_amount,
        ])->all();

        $paymentFormLink = PharmacyApp::buyMedicines($lineItems, $prescriptionRecord->id);

        return redirect()->away($paymentFormLink);

        // } else {
        //      return redirect()->route('sell-medicine.success');
        // }    
    }

    public function transactionSuccessfulPage()
    {
        $prescripionRecordId = session('prescription_record_id');
        $prescripionRecord = PrescriptionRecord::with('prescriptionMedicines.medicine')
            ->find($prescripionRecordId);
        $prescriptionMedicines = null;
        if ($prescripionRecord) {
            $prescriptionMedicines = $prescripionRecord->prescriptionMedicines;
        }
        return view('admin.sell-medicine-success', [
            'prescriptionRecordId' => $prescripionRecordId,
            'prescriptionMedicines' => $prescriptionMedicines
        ]);
    }

    public function transactionCancelledPage()
    {
        $prescripionRecordId = session('prescription_record_id');
        $prescripionRecord = PrescriptionRecord::with('prescriptionMedicines.medicine')
            ->find($prescripionRecordId);
        $prescriptionMedicines = null;
        if ($prescripionRecord) {
            $prescriptionMedicines = $prescripionRecord->prescriptionMedicines;
        }
        return view('admin.sell-medicine-cancel', ['prescriptionMedicines' => $prescriptionMedicines]);
    }
}
