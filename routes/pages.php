<?php

use App\Http\Controllers\Pages\PagesController;
use App\Http\Controllers\Pages\PageTypesController;
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

    Route::get('/page-types', [PageTypesController::class, 'index'])->name('page_types');
    Route::get('/page-types/create', [PageTypesController::class, 'create'])->name('page_types.create');
    Route::post('/page-types', [PageTypesController::class, 'store'])->name('page_types.store');
    Route::get('/page-types/{id}', [PageTypesController::class, 'show'])->name('page_types.show');
    Route::get('/page-types/{id}/edit', [PageTypesController::class, 'edit'])->name('page_types.edit');
    Route::put('/page-types/{id}', [PageTypesController::class, 'update'])->name('page_types.update');
    Route::get('/page-types/{id}/pages', [PageTypesController::class, 'pages'])->name('page_types.pages');
});

