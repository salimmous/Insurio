<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest:web')->group(function () {
    // Disable public self-registration. Redirect to login.
    Route::get('register', function () {
        return redirect()->route('login');
    })->name('register');

    Volt::route('login', 'pages.auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');

    Route::get('activate/{token}', \App\Livewire\Auth\FirstLoginWizard::class)
        ->name('activation.token');
});

Route::middleware('auth')->group(function () {
    Route::get('activation', \App\Livewire\Auth\FirstLoginWizard::class)
        ->name('activation.wizard');

    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});
