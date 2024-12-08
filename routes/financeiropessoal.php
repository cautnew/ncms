<?php

use App\Http\Controllers\Finance\FinanceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/ncms/financeiro', [FinanceController::class, 'index'])->middleware(['auth', 'verified'])->name('ncms.financeiro');

Route::get('/ncms/financeiro/addexpenses', [FinanceController::class, 'index'])->middleware(['auth', 'verified'])->name('ncms.financeiro.addexpenses');
