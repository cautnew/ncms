<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
  return Inertia::render('Welcome', [
    'canLogin' => Route::has('ncms.login'),
    'canRegister' => Route::has('ncms.register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
  ]);
});

Route::get('/ncms/dashboard', function () {
  return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('ncms.dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/ncms/profile', [ProfileController::class, 'edit'])->name('ncms.profile.edit');
  Route::patch('/ncms/profile', [ProfileController::class, 'update'])->name('ncms.profile.update');
  Route::delete('/ncms/profile', [ProfileController::class, 'destroy'])->name('ncms.profile.destroy');
});

require __DIR__ . '/pages.php';
require __DIR__ . '/manager.php';
require __DIR__ . '/ponto.php';
require __DIR__ . '/financeiropessoal.php';
require __DIR__ . '/auth.php';
