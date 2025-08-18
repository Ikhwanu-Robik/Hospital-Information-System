<?php

use Illuminate\Support\Facades\Route;

require('auth.php');

Route::get('/', function () {
    return view('index');
})->middleware('auth');
