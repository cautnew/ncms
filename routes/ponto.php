<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/ncms/ponto', function () {
  return Inertia::render('Ponto/Ponto');
})->middleware(['auth', 'verified'])->name('ncms.ponto');
