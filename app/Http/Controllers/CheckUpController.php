<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Services\CheckUp;
use Illuminate\Http\Request;
use App\Models\Specialization;
use App\Http\Controllers\Controller;

class CheckUpController extends Controller
{
    public function diagnoseForm()
    {
        return view('doctor.diagnosis-form', ['medicines' => Medicine::all()->jsonSerialize()]);
        // used jsonSerialize so the data is correctly displayed in meta tag
    }

    public function diagnosis() {
        // TODO: write doctor's diagnosis to database
        // TODO: print medicine prescription
    }

    public function queueForm()
    {
        return view("check-up-queue-form", ['specializations' => Specialization::all()]);
    }

    public function joinQueue(Request $request, CheckUp $checkUp)
    {
        $validated = $request->validate([
            'medical_record_number' => 'required|exists:patients,medical_record_number',
            'specialization' => 'required|exists:specializations,name',
        ]);

        // $queueNumber = 
        $checkUp->findDoctor($validated["specialization"])
            ->queueCheckUp($validated['medical_record_number']);

        return view('check-up-queue-number', ['queue_number' => 0]); // TODO: ->with('queue_number', $queueNumber);

        // PLAN: redefine the course of actions upon submitting the request check up form
        // Course of Actions
        // 1. The submitted data got put inside a still queue
        // 2. Check if the doctor is free AND online
        // 3. If doctor is free AND online, execute the job (move the queue)
        // 4. If doctor is not free AND online, listen for DoctorIsFree event
        // 5. If doctor is *not* online, listen for DoctorIsOnline event
        //
        // DoctorIsFree event is dispatched when doctor dismiss a patient
    }
}
