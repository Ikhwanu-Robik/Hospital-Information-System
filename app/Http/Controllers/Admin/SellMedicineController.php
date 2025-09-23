<?php

namespace App\Http\Controllers\Admin;

use App\Facades\BPJS;
use App\Facades\PharmacyApp;
use Illuminate\Http\Request;
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

        $patientHasActiveBPJS = BPJS::validatePatient($medicalRecord->patient->NIK);

        $lineItems = $prescriptionRecord->prescriptionMedicines->map(fn($pm) => [
            'price' => $pm->medicine->stripe_price_id,
            'quantity' => $pm->dose_amount,
        ])->all();

        if (!$patientHasActiveBPJS) {
            $paymentFormLink = PharmacyApp::buyMedicines($lineItems, $prescriptionRecord->id);

            return redirect()->away($paymentFormLink);
        } else {
            PharmacyApp::withBPJS($patientHasActiveBPJS)
                ->buyMedicines($lineItems, $prescriptionRecord->id);

            return redirect()->route('sell-medicine.success', ['isBPJS' => true]);
        }
    }

    public function transactionSuccessfulPage(Request $request)
    {
        $patientHasBPJS = $request->query('isBPJS', false);
        $prescripionRecordId = session('prescription_record_id');
        $prescripionRecord = PrescriptionRecord::with('prescriptionMedicines.medicine')
            ->find($prescripionRecordId);
        $prescriptionMedicines = null;
        if ($prescripionRecord) {
            $prescriptionMedicines = $prescripionRecord->prescriptionMedicines;
        }
        return view('admin.sell-medicine-success', [
            'prescriptionRecordId' => $prescripionRecordId,
            'prescriptionMedicines' => $prescriptionMedicines,
            'patientHasBPJS' => $patientHasBPJS
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
