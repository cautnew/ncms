<?php

use App\Models\Users\Gender;
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

    Route::get('/taxonomy', function () {
        return Inertia::render('taxonomy/all');
    });

    Route::get('/taxonomy/gender', function () {
        return Inertia::render('taxonomy/gender/page', ['gender_list' => Gender::all(['id', 'name', 'symbol'])]);
    });

    Route::get('/taxonomy/gender/create', function () {
        return Inertia::render('taxonomy/gender/create');
    })->name('taxonomy.gender.create');
});

Route::get('/session-test', function () {
    session(['test' => 'it works!']);

    return session('test');
});

require __DIR__ . '/pages.php';
require __DIR__ . '/routes.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
