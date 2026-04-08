<?php

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\LocalesController;
use App\Http\Controllers\Settings\Templates\TemplateController;
use App\Http\Controllers\Settings\Templates\TemplateAdjustmentsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth:sanctum')->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');

    Route::put('settings/password', [PasswordController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('password.update');

    Route::get('settings/template', [TemplateController::class, 'index']);
    Route::get('settings/template/adjustments', [TemplateAdjustmentsController::class, 'index'])->name('settings.template.adjustments');
    Route::post('settings/template/adjustments/update', [TemplateAdjustmentsController::class, 'update'])->name('settings.template.adjustments.update');

    Route::get('settings/locales', [LocalesController::class, 'edit'])->name('settings.locales.edit');
    Route::post('settings/locales', [LocalesController::class, 'update'])->name('settings.locales.update');

    Route::get('settings/appearance', function () {
        return Inertia::render('settings/appearance');
    })->name('appearance');
});
