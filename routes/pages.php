<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
  Route::get('/ncms/pages', [PontoController::class, 'index'])->name('ncms.pages');
});
