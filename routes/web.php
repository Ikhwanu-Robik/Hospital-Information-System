<?php

use App\Models\User;
use App\Models\Patient;
use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Models\DoctorProfile;
use Illuminate\Support\Facades\Route;
use App\Events\PatientWishToMeetDoctor;

require('auth.php');

Route::get('/', function () {
    return view('index');
})->middleware('auth');

Route::get('/diagnose', function () {
    return view('doctor.diagnosis-form', ['medicines' => Medicine::all()->jsonSerialize()]);
})->middleware('auth')->name('doctor.diagnosis-form');
