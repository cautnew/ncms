<?php

use App\Http\Controllers\Enterprise\EnterpriseController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/ncms/manager', function () {
  return Inertia::render('Manager/Manager');
})->middleware(['auth', 'verified'])->name('ncms.manager');

Route::get('/ncms/manager/enterprises', [EnterpriseController::class, 'index'])
  ->middleware(['auth', 'verified'])->name('ncms.manager.enterprises');

Route::get('/ncms/manager/enterprises/create', [EnterpriseController::class, 'create'])
  ->middleware(['auth', 'verified'])->name('ncms.manager.enterprises.create');

Route::post('/ncms/manager/enterprises/create', [EnterpriseController::class, 'store'])
  ->middleware(['auth', 'verified']);

Route::get('/ncms/manager/enterprises/{id}/edit', [EnterpriseController::class, 'edit'])
  ->middleware(['auth', 'verified'])->name('ncms.manager.enterprises.edit');

Route::post('/ncms/manager/enterprises/{id}/edit', [EnterpriseController::class, 'update'])
  ->middleware(['auth', 'verified']);

Route::get('/ncms/manager/enterprises/{id}/delete', [EnterpriseController::class, 'deleteConfirm'])
  ->middleware(['auth', 'verified'])->name('ncms.manager.enterprises.deleteconfirm');

Route::delete('/ncms/manager/enterprises/{id}/delete', [EnterpriseController::class, 'delete'])
  ->middleware(['auth', 'verified'])->name('ncms.manager.enterprises.delete');

Route::get('/ncms/manager/tools', function () {
  return Inertia::render('Manager/Tools/Tools');
})->middleware(['auth', 'verified'])->name('ncms.manager.tools');
