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
    Route::get('ncms/register', [RegisteredUserController::class, 'create'])
        ->name('ncms.register');

    Route::post('ncms/register', [RegisteredUserController::class, 'store']);

    Route::get('ncms/login', [AuthenticatedSessionController::class, 'create'])
        ->name('ncms.login');

    Route::post('ncms/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('ncms/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('ncms.password.request');

    Route::post('ncms/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('ncms.password.email');

    Route::get('ncms/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('ncms.password.reset');

    Route::post('ncms/reset-password', [NewPasswordController::class, 'store'])
        ->name('ncms.password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('ncms/verify-email', EmailVerificationPromptController::class)
        ->name('ncms.verification.notice');

    Route::get('ncms/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('ncms.verification.verify');

    Route::post('ncms/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('ncms.verification.send');

    Route::get('ncms/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('ncms.password.confirm');

    Route::post('ncms/confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('ncms/password', [PasswordController::class, 'update'])->name('ncms.password.update');

    Route::post('ncms/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('ncms.logout');
});
