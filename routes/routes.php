<?php

use App\Http\Controllers\Routes\RoutesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/routes', [RoutesController::class, 'index'])->name('routes');
    Route::get('/routes/cache', [RoutesController::class, 'details'])->name('routes.cache.details');
    Route::post('/routes/cache', [RoutesController::class, 'cache'])->name('routes.cache');
    Route::delete('/routes/cache', [RoutesController::class, 'clear'])->name('routes.cache.clear');
});
