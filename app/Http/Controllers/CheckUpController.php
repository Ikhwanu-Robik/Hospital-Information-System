<?php

namespace App\Http\Controllers;

use App\Enums\CheckUpStatus;
use App\Enums\PaymentStatus;
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
    public function setDoctorPingIntervalForm()
    {
        $currentInterval = Setting::where('key', 'doctor-ping-interval')->first();
        $currentInterval = $currentInterval ? $currentInterval->value : null;

        return view('admin.set-doctor-ping-interval', ['currentInterval' => $currentInterval]);
    }

    public function setDoctorPingInterval(Request $request)
    {
        $validated = $request->validate([
            'ping_interval' => 'required|numeric'
        ]);

        $doctorPingInterval = Setting::where('key', 'doctor-ping-interval')->first();
        if ($doctorPingInterval) {
            $doctorPingInterval->value = $validated['ping_interval'];
            $doctorPingInterval->save();
        } else {
            Setting::create([
                'doctor-ping-interval',
                $validated['ping_interval']
            ]);
        }

        return back();
    }

    public function doctorPing(DoctorProfile $doctorProfile)
    {
        $doctorProfile->doctorOnlineStatus->last_seen_at = now();
        $doctorProfile->doctorOnlineStatus->save();
    }

    public function diagnoseForm()
    {
        if (!Auth::user()->can(['accept patient', 'prescribe medicine'])) {
            abort(403);
        }
        $doctorPingInterval = Setting::where('key', 'doctor-ping-interval')->first('value');
        $doctorPingInterval = $doctorPingInterval->value;

        return view('doctor.diagnosis-form', ['doctorPingInterval' => $doctorPingInterval, 'medicines' => Medicine::all()->jsonSerialize()]);
        // used jsonSerialize so the data is correctly displayed in meta tag
    }

    public function diagnosis(Request $request)
    {
        $validated = $request->validate([
            'doctor_profile_id' => 'required|exists:doctor_profiles,id',
            'queue_id' => 'required|exists:check_up_queues,id',
            'medical_record_number' => 'required|exists:patients,medical_record_number',
            'complaint' => 'required',
            'diagnosis' => 'required',
            'medicine_id' => 'sometimes|array', // TODO: complete the validation
            'dose_amount' => 'sometimes|array',
            'frequency' => 'sometimes|array',
            'medicine_id.*' => 'exists:medicines,id'
        ]);

        $patient = Patient::where('medical_record_number', $validated['medical_record_number'])->first();
        $MedicalRecord = MedicalRecord::create([
            'patient_id' => $patient->id,
            'doctor_profile_id' => $validated['doctor_profile_id'],
            'complaint' => $validated['complaint'],
            'diagnosis' => $validated['diagnosis']
        ]);
        if (isset($validated['medicine_id'])) {
            // TODO: set status to FINISHED if patient has BPJS
            $prescriptionRecord = $MedicalRecord->prescriptionRecord()->create([
                'payment_status' => PaymentStatus::PENDING->value
            ]);
            for ($i = 0; $i < count($validated['medicine_id']); $i++) {
                $prescriptionRecord->prescriptionMedicines()->create([
                    'medicine_id' => $validated['medicine_id'][$i],
                    'dose_amount' => $validated['dose_amount'][$i],
                    'frequency' => $validated['frequency'][$i]
                ]);
            }
        }

        $checkUpQueue = CheckUpQueue::find($validated['queue_id']);
        $checkUpQueue->status = CheckUpStatus::FINISHED->value;
        $checkUpQueue->save();
        DoctorIsFree::dispatch(DoctorProfile::find($validated['doctor_profile_id']));

        if (isset($validated['medicine_id'])) {
            return redirect()
                ->route('medicine-prescription.print')
                ->with(
                    'prescriptions',
                    $prescriptionRecord
                        ->prescriptionMedicines()
                        ->with('medicine')
                        ->get()
                );
        } else {
            return back();
        }
    }

    public function getPatientMedicalRecords(Patient $patient)
    {
        return MedicalRecord::with('prescriptionRecord.prescriptionMedicines.medicine', 'doctorProfile.specialization')
            ->where('patient_id', $patient->id)
            ->get()->toArray();
        // the eager loaded relationships will be missing without toArray()
    }

    public function printPrescriptionPage()
    {
        $printer = Setting::where('key', 'queue-app-default-printer')->first();
        $printerName = $printer ? $printer->value : null;

        return view('doctor.print-medicine-prescriptions', ['printerName' => $printerName]);
    }

    public function callPatient(Request $request)
    {
        $validated = $request->validate([
            'doctor_profile_id' => 'required|exists:doctor_profiles,id'
        ]);

        // we can simply dispatch this event without passing the patient to be called
        // because the patient is certainly the patient that has waited the longest
        DoctorIsFree::dispatch(DoctorProfile::find($validated['doctor_profile_id']));

        return back();
    }

    public function skipPatient(Request $request)
    {
        $validated = $request->validate([
            'doctor_profile_id' => 'required|exists:doctor_profiles,id',
            'queue_id_skip' => 'required|exists:check_up_queues,id',
        ]);

        $checkUpQueue = CheckUpQueue::find($validated['queue_id_skip']);
        $checkUpQueue->status = CheckUpStatus::SKIPPED->value;
        $checkUpQueue->save();
        DoctorIsFree::dispatch(DoctorProfile::find($validated['doctor_profile_id']));

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
        // TODO: validate the printer's name
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
