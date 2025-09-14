<?php

namespace App\Http\Controllers;

use App\Models\Locket;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\Setting;
use App\Services\QueueApp;
use App\Events\DoctorIsFree;
use App\Models\CheckUpQueue;
use Illuminate\Http\Request;
use App\Models\DoctorProfile;
use App\Models\MedicalRecord;
use App\Models\Specialization;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CheckUpController extends Controller
{
    public function diagnoseForm()
    {
        if (!Auth::user()->can(['accept patient', 'prescribe medicine'])) {
            abort(403);
        }

        return view('doctor.diagnosis-form', ['medicines' => Medicine::all()->jsonSerialize()]);
        // used jsonSerialize so the data is correctly displayed in meta tag
    }

    public function diagnosis(Request $request)
    {
        $validated = $request->validate([
            'doctor_profile_id' => 'required|exists:doctor_profiles,id',
            'queue_id' => 'required|exists:check_up_queues,id',
            'medical_record_number' => 'required|exists:patients,medical_record_number',
            // 'complaint' => 'required',
            // 'diagnosis' => 'required',
            'medicine_id[]' => 'sometimes|array', // TODO: complete the validation
            'dose_amount[]' => 'sometimes|array',
            'frequency[]' => 'sometimes|array'
        ]);

        CheckUpQueue::find($validated['queue_id'])->delete();
        DoctorIsFree::dispatch(DoctorProfile::find($validated['doctor_profile_id']));

        // TODO: write doctor's diagnosis to database
        // TODO: print medicine prescription

        return back();
    }

    public function queueForm()
    {
        return view("check-up-queue-form", ['specializations' => Specialization::all()]);
    }

    public function joinQueue(Request $request, QueueApp $queueApp)
    {
        $validated = $request->validate([
            'medical_record_number' => 'required|exists:patients,medical_record_number',
            'specialization' => 'required|exists:specializations,name',
        ]);

        $specialization = Specialization::where('name', $validated['specialization'])->first();

        $patient = Patient::where('medical_record_number', $validated['medical_record_number'])->first();

        $queueNumber = $queueApp->putInQueue($patient, $specialization);

        $printer = Setting::where('key', 'queue-app-default-printer')->first();
        $printerName = $printer ? $printer->value : null;

        return view('check-up-queue-number', ['queueNumber' => $queueNumber, 'printerName' => $printerName]);
    }

    public function locketPage(Request $request)
    {
        $locket = Locket::find($request->query('id'));

        return view('locket-page', ['locket' => $locket]);
    }

    public function getOldestPatient(Request $request, DoctorProfile $doctorProfile)
    {
        //TODO: add some security to this, preferably with CSRF token
        $earliestQueue = CheckUpQueue::where('doctor_profile_id', $doctorProfile->id)
            ->with('patient')->oldest()->first();
        if ($earliestQueue) {
            $patient = $earliestQueue->patient;
            $medicalRecords = MedicalRecord::with('prescriptionRecord.prescriptionMedicines.medicine', 'doctorProfile.specialization')
                ->where('patient_id', $patient->id)
                ->get()->toArray();

            return [
                'queueId' => $earliestQueue->id,
                'patient' => $patient,
                'medicalRecords' => $medicalRecords
            ];
        }

        return response([
            'message' => 'No patient'
        ], 404);
    }

    public function setPrinterForm()
    {
        if (!backpack_user()->hasRole('super admin')) {
            return abort(403);
        }

        $printer = Setting::where('key', 'queue-app-default-printer')->first();
        $printerName = $printer ? $printer->value : null;
        return view('admin.set-queue-app-printer', ['currentPrinter' => $printerName]);
    }

    public function setQueueNumberDefaultPrinter(Request $request)
    {
        // would love to validate, but don't know how
        $validated = $request->validate([
            'printer' => 'required'
        ]);
        if (Setting::where('key', 'queue-app-default-printer')->exists()) {
            $queueAppDefaultPrinter = Setting::where('key', 'queue-app-default-printer')->first();
            $queueAppDefaultPrinter->value = $validated['printer'];
            $queueAppDefaultPrinter->save();
        } else {
            Setting::create(['key' => 'queue-app-default-printer', 'value' => $validated['printer']]);
        }
        return view('admin.queue-app-printer-setted', ['printer' => $validated['printer']]);
    }
}
