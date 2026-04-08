<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\Admin\TemplateValidationController;
use App\Http\Controllers\Api\Admin\PageController;
use App\Http\Controllers\Api\Admin\RouteCacheController;
use App\Http\Controllers\Api\Admin\TaxonomyController;
use App\Http\Controllers\Api\Admin\TaxonomyTermController;

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::get('/templates/validate-class', [TemplateValidationController::class, 'validateClass']);
    Route::apiResource('pages', PageController::class);

    // Route Cache API
    Route::get('/routes/cache', [RouteCacheController::class, 'status']);
    Route::post('/routes/cache', [RouteCacheController::class, 'store']);
    Route::delete('/routes/cache', [RouteCacheController::class, 'destroy']);

    // ── Taxonomy API ─────────────────────────────────────────────
    Route::prefix('taxonomies')->group(function () {
        Route::get('/', [TaxonomyController::class, 'index']);
        Route::post('/', [TaxonomyController::class, 'store']);
        Route::get('/{slug}', [TaxonomyController::class, 'show']);
        Route::put('/{slug}', [TaxonomyController::class, 'update']);
        Route::delete('/{slug}', [TaxonomyController::class, 'destroy']);
        Route::get('/{slug}/history', [TaxonomyController::class, 'history']);

        // Terms
        Route::get('/{slug}/terms', [TaxonomyTermController::class, 'index']);
        Route::post('/{slug}/terms', [TaxonomyTermController::class, 'store']);
        Route::get('/{slug}/terms/{termSlug}', [TaxonomyTermController::class, 'show']);
        Route::put('/{slug}/terms/{termSlug}', [TaxonomyTermController::class, 'update']);
        Route::delete('/{slug}/terms/{termSlug}', [TaxonomyTermController::class, 'destroy']);
        Route::get('/{slug}/terms/{termSlug}/history', [TaxonomyTermController::class, 'history']);
    });
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

require_once __DIR__ . '/api_brutos.php';
