<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/pp', function () {
    return view('home');
});

require __DIR__ . '/routes.php';
require __DIR__ . '/globalvars.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
