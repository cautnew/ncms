<?php

use App\Http\Controllers\Finance\FinanceController;
use App\Http\Controllers\Finance\ExpenseController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/ncms/financeiro', [FinanceController::class, 'index'])->middleware(['auth', 'verified'])->name('ncms.financeiro');

Route::get('/ncms/financeiro/addexpenses', [ExpenseController::class, 'index'])->middleware(['auth', 'verified'])->name('ncms.financeiro.addexpenses');
