<?php

use App\Http\Controllers\Users\ProfileController;
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

Route::get('/ncms/teste-blade', function () {
  return view('teste');
});

Route::get('/ncms/dashboard', function () {
  return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('ncms.dashboard');

Route::middleware('auth')->group(function () {
  Route::get('/ncms/profile', [ProfileController::class, 'edit'])->name('ncms.profile.edit');
  Route::patch('/ncms/profile', [ProfileController::class, 'update'])->name('ncms.profile.update');
  Route::delete('/ncms/profile', [ProfileController::class, 'destroy'])->name('ncms.profile.destroy');
});

require __DIR__ . '/../packages/custom/my-financeiro/routes.php';
require __DIR__ . '/../packages/custom/my-ponto/routes.php';
require __DIR__ . '/../packages/core/pages/routes.php';

require __DIR__ . '/manager.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/dinendpoints.php';
