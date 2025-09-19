<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::get('/teste1', function () {
    return view('teste', ['pageTitle' => 'Olha isso!']);
})->name('teste1');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

Route::get('/session-test', function () {
    session(['test' => 'it works!']);

    return session('test');
});

require __DIR__ . '/pages.php';
require __DIR__ . '/routes.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
