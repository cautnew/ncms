<?php

use App\Http\Controllers\Pages\PagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::middleware('auth')->group(function () {
    Route::get('/pages', [PagesController::class, 'index'])->name('pages');
    Route::get('/pages/create', [PagesController::class, 'create'])->name('pages.create');
    Route::post('/pages', [PagesController::class, 'store'])->name('pages.store');
    Route::get('/pages/{id}', [PagesController::class, 'show'])->name('pages.show');
    Route::get('/pages/{id}/edit', [PagesController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{id}', [PagesController::class, 'update'])->name('pages.update');
    Route::delete('/pages/{id}', [PagesController::class, 'destroy'])->name('pages.destroy');
});
