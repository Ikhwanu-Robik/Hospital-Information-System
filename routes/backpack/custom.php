<?php

use App\Http\Controllers\Admin\SellMedicineController;
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
    });

    Route::crud('doctor-profile', 'DoctorProfileCrudController');
    Route::crud('drug-class', 'DrugClassCrudController');
    Route::crud('medical-record', 'MedicalRecordCrudController');
    Route::crud('medicine', 'MedicineCrudController');
    Route::crud('medicine-form', 'MedicineFormCrudController');
    Route::crud('medicine-route', 'MedicineRouteCrudController');
    Route::crud('patient-profile', 'PatientProfileCrudController');
    Route::crud('pharmacist-profile', 'PharmacistProfileCrudController');
    Route::crud('prescription-medicine', 'PrescriptionMedicineCrudController');
    Route::crud('prescription-record', 'PrescriptionRecordCrudController');
    Route::crud('specialization', 'SpecializationCrudController');
    Route::crud('user', 'UserCrudController');
    Route::crud('doctor-schedule', 'DoctorScheduleCrudController');
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
