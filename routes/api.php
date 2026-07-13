<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Pages\PageController;
use App\Http\Middleware\UserAdminRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', function (Request $request) {
        return $request->user();
    })->name('api.users');

    Route::get('/user', function (Request $request) {
        return response()->json([
            'user' => $request->user(),
        ]);
    })->name('api.user');

    Route::get('/pages', [PageController::class, 'list'])->name('api.page.list');
    Route::get('/website/{website_id?}/pages', [PageController::class, 'list'])->name('api.website.page.list');
});

Route::middleware(['auth:sanctum', UserAdminRequests::class])->group(function () {
    Route::post('/page/create', [PageController::class, 'create'])->name('api.page.create');
    Route::post('/page/update', [PageController::class, 'update'])->name('api.page.update');
});
