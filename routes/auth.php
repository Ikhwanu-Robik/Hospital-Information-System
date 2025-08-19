<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('login-form');
})->name('login');

Route::post('/login', function (Request $request) {
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);
    if (Auth::attempt($validated)) {
        return redirect()->intended();
    }
})->name('login.action');
