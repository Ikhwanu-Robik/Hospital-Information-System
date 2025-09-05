<?php

use App\Http\Controllers\CheckUpController;
use Illuminate\Support\Facades\Route;

require('auth.php');

Route::get('/diagnose', [CheckUpController::class, 'diagnoseForm'])->middleware('auth')->name('doctor.diagnosis-form');

Route::post('/diagnosis', [CheckUpController::class, 'diagnosis'])->middleware('auth')->name('doctor.diagnosis');

Route::get('/queue', [CheckUpController::class, 'queueForm'])->name('check-up-queue-form');

Route::post('/queue', [CheckUpController::class, 'joinQueue'])->name('join-check-up-queue');