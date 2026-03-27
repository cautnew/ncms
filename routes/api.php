<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\Admin\TemplateValidationController;
use App\Http\Controllers\Api\Admin\PageController;
use App\Http\Controllers\Api\Admin\RouteCacheController;

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::get('/templates/validate-class', [TemplateValidationController::class, 'validateClass']);
    Route::apiResource('pages', PageController::class);
    
    // Route Cache API
    Route::get('/routes/cache', [RouteCacheController::class, 'status']);
    Route::post('/routes/cache', [RouteCacheController::class, 'store']);
    Route::delete('/routes/cache', [RouteCacheController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/users', function (Request $request) {
        return $request->user()::all();
    });

    Route::get('/login/users', function (Request $request) {
        return $request->user()::all();
    });

    Route::get('/login/token', function (Request $request) {
        return response()->json([
            'message' => 'User authenticated',
            'user-id' => $request->user()->id
        ]);
    });
});

Route::post('/login/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

require_once __DIR__.'/api_brutos.php';
