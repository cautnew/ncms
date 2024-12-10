<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('ncms/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('ncms.dashboard');

Route::view('ncms/profile', 'profile')
    ->middleware(['auth'])
    ->name('ncms.profile');

require __DIR__.'/auth.php';
