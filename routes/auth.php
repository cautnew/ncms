<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
  Route::get('/ncms/auth/register', [RegisteredUserController::class, 'create'])
    ->name('ncms.register');

  Route::post('/ncms/auth/register', [RegisteredUserController::class, 'store']);

  Route::get('/ncms/auth/login', [AuthenticatedSessionController::class, 'create'])
    ->name('ncms.login');

  // Fix redirect to login page because of Illuminate\Routing\UrlGenerator::to() method
  Route::redirect('/login', '/ncms/auth/login')->name('login');
  // Route::get('/login', function () {
  //   return redirect()->route('ncms.login');
  // })->name('login');

  Route::post('/ncms/auth/login', [AuthenticatedSessionController::class, 'store']);

  Route::get('/ncms/auth/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('ncms.password.request');

  Route::post('/ncms/auth/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('ncms.password.email');

  Route::get('/ncms/auth/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->name('ncms.password.reset');

  Route::post('/ncms/auth/reset-password', [NewPasswordController::class, 'store'])
    ->name('ncms.password.store');
});

Route::middleware('auth')->group(function () {
  Route::get('/ncms/auth/verify-email', EmailVerificationPromptController::class)
    ->name('ncms.verification.notice');

  Route::get('/ncms/auth/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('ncms.verification.verify');

  Route::post('/ncms/auth/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('ncms.verification.send');

  Route::get('/ncms/auth/confirm-password', [ConfirmablePasswordController::class, 'show'])
    ->name('ncms.password.confirm');

  Route::post('/ncms/auth/confirm-password', [ConfirmablePasswordController::class, 'store']);

  Route::put('/ncms/auth/password', [PasswordController::class, 'update'])->name('ncms.password.update');

  Route::post('/ncms/auth/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('ncms.logout');
});
