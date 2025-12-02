<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/register', function () {
    return view('registration');
})->name('registration');

Route::post('/register', [App\Http\Controllers\RegistrationController::class, 'store'])->name('registration.store');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [App\Http\Controllers\RegistrationController::class, 'login'])->name('login');

Route::get('/subscription', fn() => Auth::check() ? view('subscription') : back()->with('error', 'You need to login first'))->name('subscription');

Route::post('/subscription', [App\Http\Controllers\RegistrationController::class, 'subscribe'])->name('subscription.store');

Route::post('/qr-scan', [App\Http\Controllers\QRScannerController::class, 'scan'])->name('qr.scan');

Route::get('paymentMail', function () {
    return view('Mailler.payment_submitted');
})->name('paymentMail');