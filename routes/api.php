<?php

use App\Http\Controllers\CheckUpController;
use Illuminate\Support\Facades\Route;

Route::get('/doctors/{doctorProfile}/patients/earliest', [CheckUpController::class, 'getOldestPatient']);
