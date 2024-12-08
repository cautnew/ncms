<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/ncms/pages', function () {
  return Redirect::route('ncms.pages.contents');
})->middleware(['auth', 'verified'])->name('ncms.pages');

Route::get('/ncms/pages/contents', function () {
  return Inertia::render('Pages/Contents');
})->middleware(['auth', 'verified'])->name('ncms.pages.contents');

Route::get('/ncms/pages/models', function () {
  return Inertia::render('Pages/Models');
})->middleware(['auth', 'verified'])->name('ncms.pages.models');
