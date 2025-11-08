<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckUpController;
use App\Http\Controllers\Admin\SellMedicineController;

require('auth.php');

Route::view('/', 'index');

Route::middleware('auth')->group(function () {
    Route::post('/doctors/{doctorProfile}/ping', [CheckUpController::class, 'doctorPing']);

    Route::get('/diagnose', [CheckUpController::class, 'diagnoseForm'])->name('doctor.diagnosis-form');
    Route::get('/diagnosis/patient/{patient}/medical-records', [CheckUpController::class, 'getPatientMedicalRecords'])->name('patient.medical-records');

    Route::post('/diagnosis', [CheckUpController::class, 'diagnosis'])->name('doctor.diagnosis');
    Route::post('/diagnosis/patient/call', [CheckUpController::class, 'callPatient'])->name('patient.check-up.call');
    Route::post('/diagnosis/skip', [CheckUpController::class, 'skipPatient'])->name('patient.check-up.skip');
    
    Route::get('/diagnosis/prescription/print', [CheckUpController::class, 'printPrescriptionPage'])->name('medicine-prescription.print');
});

Route::get('/queue', [CheckUpController::class, 'queueForm'])->name('check-up-queue-form');
Route::post('/queue', [CheckUpController::class, 'joinQueue'])->name('join-check-up-queue');

Route::get('/locket', [CheckUpController::class, 'locketPage'])->name('locket');
Route::get('/locket/all', [CheckUpController::class, 'allLocket'])->name('locket.all');

Route::get('/prescriptions/{prescriptionRecord}', [SellMedicineController::class, 'manualFetchPrescription']);