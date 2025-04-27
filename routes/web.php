<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/ncms/dashboard', function () {
        return Inertia::render('ncms/dashboard');
    })->name('ncms.dashboard');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/ponto.php';
require __DIR__ . '/auth.php';
