<?php

use App\Http\Controllers\Admin\SellMedicineController;
use App\Http\Controllers\CheckUpController;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes    

    Route::group(['prefix' => '/medicines/dispense'], function () {
        Route::get('/', [SellMedicineController::class, 'index'])->name('sell-medicine');
        Route::get('/search-medicine', [SellMedicineController::class, 'searchMedicines'])->name('search-medicine');
        Route::post('/buy', [SellMedicineController::class, 'buyMedicines'])->name('buy-medicines');
        Route::get('/successful', [SellMedicineController::class, 'transactionSuccessfulPage'])->name('sell-medicine.success');
        Route::get('/cancelled', [SellMedicineController::class, 'transactionCancelledPage'])->name('sell-medicine.cancelled');
    });

    Route::get('/queue/printer', [CheckUpController::class, 'setPrinterForm'])->name('queue.printer.form');
    Route::post('/queue/printer', [CheckUpController::class, 'setQueueNumberDefaultPrinter'])->name('queue.printer.set');

    Route::get('/doctors/ping-interval', [CheckUpController::class, 'setDoctorPingIntervalForm'])->name('doctors.ping-interval-form');
    Route::post('/doctors/ping-interval', [CheckUpController::class, 'setDoctorPingInterval'])->name('doctors.ping-interval');

    Route::crud('doctor-profile', 'DoctorProfileCrudController');
    Route::crud('drug-class', 'DrugClassCrudController');
    Route::crud('medical-record', 'MedicalRecordCrudController');
    Route::crud('medicine', 'MedicineCrudController');
    Route::crud('medicine-form', 'MedicineFormCrudController');
    Route::crud('medicine-route', 'MedicineRouteCrudController');
    Route::crud('pharmacist-profile', 'PharmacistProfileCrudController');
    Route::crud('prescription-medicine', 'PrescriptionMedicineCrudController');
    Route::crud('prescription-record', 'PrescriptionRecordCrudController');
    Route::crud('specialization', 'SpecializationCrudController');
    Route::crud('user', 'UserCrudController');
    Route::crud('doctor-schedule', 'DoctorScheduleCrudController');
    Route::crud('patient', 'PatientCrudController');
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
