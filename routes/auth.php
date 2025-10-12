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
        $intendedUrl = session()->get('url.intended');
        $user = Auth::user();

        if ($intendedUrl) {
            return redirect()->intended();
        } else if ($user->hasRole('doctor')) {
            return redirect()->route('doctor.diagnosis-form');
        }
        // other roles are not redirected here because 
        // they use backpack's login system
        // and therefore a different login controller
    }
})->name('login.action');

Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');