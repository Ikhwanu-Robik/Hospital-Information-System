<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckUpController;

require('auth.php');

Route::post('/doctors/{doctorProfile}/ping', [CheckUpController::class, 'doctorPing'])->middleware('auth');

Route::get('/diagnose', [CheckUpController::class, 'diagnoseForm'])->middleware('auth')->name('doctor.diagnosis-form');

Route::post('/diagnosis', [CheckUpController::class, 'diagnosis'])->middleware('auth')->name('doctor.diagnosis');

Route::post('/diagnosis/skip', [CheckUpController::class, 'skipPatient'])->middleware('auth')->name('patient.check-up.skip');

Route::post('/diagnosis/patient/call', [CheckUpController::class, 'callPatient'])->middleware('auth')->name('patient.check-up.call');

Route::get('/diagnosis/prescription/print', [CheckUpController::class, 'printPrescriptionPage'])->middleware('auth')->name('medicine-prescription.print');

Route::get('/queue', [CheckUpController::class, 'queueForm'])->name('check-up-queue-form');

Route::post('/queue', [CheckUpController::class, 'joinQueue'])->name('join-check-up-queue');

Route::get('/locket', [CheckUpController::class, 'locketPage']);
