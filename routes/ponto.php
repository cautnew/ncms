<?php

use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::redirect('/ncms/ponto', '/ponto/profile');

    Route::get('/ponto/profile', [ProfileController::class, 'edit'])->name('ponto.edit');
});
