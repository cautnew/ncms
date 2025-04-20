<?php

use App\Http\Controllers\NCMS\Route\RouteController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/ncms/routes', [RouteController::class, 'index'])
        ->name('ncms.routes');

    Route::post('/ncms/routes', [RouteController::class, 'store'])
        ->name('ncms.routes.store');

    Route::get('/ncms/routes/create', [RouteController::class, 'create'])
        ->name('ncms.routes.create');

    Route::post('/ncms/routes/{route}/edit', [RouteController::class, 'update'])
        ->name('ncms.routes.update');

    Route::get('/ncms/routes/{route}/edit', [RouteController::class, 'edit'])
        ->name('ncms.routes.edit');

    Route::post('/ncms/routes/{route}/update/name', [RouteController::class, 'updateName'])
        ->name('ncms.routes.update.name');

    Route::delete('/ncms/routes/{route}', [RouteController::class, 'destroy'])
        ->name('ncms.routes.destroy');

    Route::post('/ncms/routes/{route}/update/is_read_only', [RouteController::class, 'updateIsReadOnly'])
        ->name('ncms.routes.update.is_read_only');

    Route::post('/ncms/routes/{route}/update/is_protected', [RouteController::class, 'updateIsProtected'])
        ->name('ncms.routes.update.is_protected');

    Route::post('/ncms/routes/{route}/update/parameter/{parameter}', [RouteController::class, 'updateIsProtected'])
        ->name('ncms.routes.update.parameter');
});

Route::get('/ncms/routes/name/{name}', [RouteController::class, 'findByName'])
    ->name('ncms.routes.findByName');

Route::get('/ncms/routes/id/{id}', [RouteController::class, 'findById'])
    ->name('ncms.routes.findById');
