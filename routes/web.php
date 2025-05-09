<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::get('/testes', function () {
    return view('temp');
})->middleware([App\Http\Middleware\TesteImports::class]);

Route::get('/teste', function () {
    return view('temps');
})->middleware([App\Http\Middleware\TesteImports::class]);

Route::get('/meucurriculo', function () {
    return view('meucurriculo');
})->middleware([App\Http\Middleware\TesteImports::class]);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/ncms/dashboard', function () {
        return Inertia::render('ncms/dashboard');
    })->name('ncms.dashboard');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/ponto.php';
require __DIR__ . '/auth.php';
