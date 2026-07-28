<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

Route::get('/artigos', function () {
    return 'foi';
})->name('artigos');

require __DIR__.'/pages.php';
require __DIR__.'/settings.php';
