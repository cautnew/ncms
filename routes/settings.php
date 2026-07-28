<?php

use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\SecurityController;
use App\Http\Controllers\Settings\WebsitesSettingsController;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'verified'])->prefix('settings')->group(function () {
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('proflie.destroy');

    Route::get('security', [SecurityController::class, 'edit'])
        ->middleware(RequirePassword::class)
        ->name('security.edit');

    Route::put('password', [SecurityController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('user-password.update');

    Route::inertia('appearance', 'settings/appearance')->name('appearance.edit');

    Route::get('websites', [WebsitesSettingsController::class, 'index'])->name('settings.websites.index');
    Route::get('websites/create', [WebsitesSettingsController::class, 'create'])->name('settings.websites.create');
    Route::patch('websites', [WebsitesSettingsController::class, 'update'])->name('settings.websites.update');
});

Route::get('.well-known/passkey-endpoints', function () {
    return response()->json([
        'enroll' => route('security.edit'),
        'manage' => route('security.edit'),
    ]);
})->name('well-known.passkeys');
