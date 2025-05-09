<?php

use App\Http\Controllers\HR\PontoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/ncms/ponto', [PontoController::class, 'index'])->name('ncms.ponto');
    Route::get('/ncms/ponto/registrar', [PontoController::class, 'create'])->name('ncms.ponto.registrar');
    Route::post('/ncms/ponto/registrar', [PontoController::class, 'store']);
});
