<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
  Volt::route('ncms/register', 'pages.auth.register')
    ->name('ncms.register');

  Volt::route('ncms/login', 'pages.auth.login')
    ->name('ncms.login');

  Volt::route('login', 'pages.auth.login')
    ->name('login');

  Volt::route('ncms/forgot-password', 'pages.auth.forgot-password')
    ->name('ncms.password.request');

  Volt::route('ncms/reset-password/{token}', 'pages.auth.reset-password')
    ->name('ncms.password.reset');
});

Route::middleware('auth')->group(function () {
  Volt::route('ncms/verify-email', 'pages.auth.verify-email')
    ->name('ncms.verification.notice');

  Route::get('ncms/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('ncms.verification.verify');

  Volt::route('ncms/confirm-password', 'pages.auth.confirm-password')
    ->name('ncms.password.confirm');
});
