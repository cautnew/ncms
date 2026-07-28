<?php

use App\Http\Controllers\Pages\PageController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\SecurityController;
use App\Http\Controllers\Settings\WebsitesSettingsController;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('pages')->group(function () {
    Route::get('/', [PageController::class, 'index'])->name('pages.index');
});
