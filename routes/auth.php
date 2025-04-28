<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/ncms/register', [RegisteredUserController::class, 'create'])
        ->name('ncms.register');

    Route::post('/ncms/register', [RegisteredUserController::class, 'store']);

    Route::redirect('login', '/ncms/login')->name('login');

    Route::get('/ncms/login', [AuthenticatedSessionController::class, 'create'])
        ->name('ncms.login');

    Route::post('/ncms/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/ncms/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('ncms.password.request');

    Route::post('/ncms/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('ncms.password.email');

    Route::get('/ncms/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('ncms.password.reset');

    Route::post('/ncms/reset-password', [NewPasswordController::class, 'store'])
        ->name('ncms.password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
