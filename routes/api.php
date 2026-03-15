<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

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
