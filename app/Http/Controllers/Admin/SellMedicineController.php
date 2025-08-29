<?php

namespace App\Http\Controllers\Admin;

use App\Facades\BPJS;
use Illuminate\Http\Request;
use App\Facades\BuyMedicines;
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

    public function searchMedicines(Request $request)
    {
        $prescription = PrescriptionRecord::find($request->query('id'));
        $prescriptionMedicines = PrescriptionRecord::with(['prescriptionMedicines.medicine'])
            ->find($request->query('id'))->prescriptionMedicines;

        return view('admin.sell-medicine', ['prescription' => $prescription, 'prescriptionMedicines' => $prescriptionMedicines]);
    }

    public function buyMedicines(BuyMedicineRequest $buyMedicineRequest)
    {
        $prescriptionRecord = PrescriptionRecord::find($buyMedicineRequest['id']);
        $medicalRecord = PrescriptionRecord::with('medicalRecord.patient')
            ->find($buyMedicineRequest['id'])->medicalRecord;
        if (!BPJS::validatePatient($medicalRecord->patient->BPJS_number)) {
            BuyMedicines::withPrescription($prescriptionRecord)->buy();
            BuyMedicines::withPrescription($prescriptionRecord)->reduceMedicineStock();
        } else {
            BuyMedicines::withPrescription($prescriptionRecord)->reduceMedicineStock();
        }    
    }
}
