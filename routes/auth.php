<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('login-form');
})->name('login');

// this login function is currently only used by doctor
// since the other roles use Backpack's login controller
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
        // TODO: move other roles login into this function
        // that is, move them away from Backpack's login controller
        // TODO: convert this function into a Controller
    }
})->name('login.action');

Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');