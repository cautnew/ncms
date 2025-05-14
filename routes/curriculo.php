<?php

use App\Http\Controllers\Curriculo\CurriculoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::redirect('/ncms/curriculo', '/ncms/curriculos');
    Route::get('/ncms/curriculos', [CurriculoController::class, 'index'])->name('ncms.curriculo.curriculos');
    Route::get('/ncms/curriculo/add', [CurriculoController::class, 'create'])->name('ncms.curriculo.add');
});
