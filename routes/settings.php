<?php

use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::redirect('/ncms/settings', '/ncms/settings/profile');

    Route::get('/ncms/settings/profile', [ProfileController::class, 'edit'])->name('ncms.profile.edit');
    Route::patch('/ncms/settings/profile', [ProfileController::class, 'update'])->name('ncms.profile.update');
    Route::delete('/ncms/settings/profile', [ProfileController::class, 'destroy'])->name('ncms.profile.destroy');

    Route::get('/ncms/settings/password', [PasswordController::class, 'edit'])->name('ncms.password.edit');
    Route::put('/ncms/settings/password', [PasswordController::class, 'update'])->name('ncms.password.update');

    Route::get('/ncms/settings/appearance', function () {
        return Inertia::render('ncms/settings/appearance');
    })->name('ncms.settings.appearance');
});
