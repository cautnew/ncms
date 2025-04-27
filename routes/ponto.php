<?php

use App\Http\Controllers\HR\PontoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::get('/ponto', [PontoController::class, 'index'])->name('ponto');
    Route::get('/ponto/registrar', [PontoController::class, 'create'])->name('ponto.registrar');
    Route::post('/ponto/registrar', [PontoController::class, 'store']);
});
