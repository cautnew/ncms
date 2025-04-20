<?php

use App\Http\Controllers\NCMS\GlobalVars\GlobalVarsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/ncms/globalvars', [GlobalVarsController::class, 'index'])
        ->name('ncms.globalvars');

    Route::post('/ncms/globalvars', [GlobalVarsController::class, 'store'])
        ->name('ncms.globalvars.store');

    Route::get('/ncms/globalvars/create', [GlobalVarsController::class, 'create'])
        ->name('ncms.globalvars.create');

    Route::post('/ncms/globalvars/{globalvar}/edit', [GlobalVarsController::class, 'update'])
        ->name('ncms.globalvars.update');

    Route::get('/ncms/globalvars/{globalvar}/edit', [GlobalVarsController::class, 'edit'])
        ->name('ncms.globalvars.edit');

    Route::post('/ncms/globalvars/{globalvar}/update/name', [GlobalVarsController::class, 'updateName'])
        ->name('ncms.globalvars.update.name');

    Route::delete('/ncms/globalvars/{globalvar}', [GlobalVarsController::class, 'destroy'])
        ->name('ncms.globalvars.destroy');

    Route::post('/ncms/globalvars/{globalvar}/update/is_read_only', [GlobalVarsController::class, 'updateIsReadOnly'])
        ->name('ncms.globalvars.update.is_read_only');

    Route::post('/ncms/globalvars/{globalvar}/update/is_protected', [GlobalVarsController::class, 'updateIsProtected'])
        ->name('ncms.globalvars.update.is_protected');

    Route::post('/ncms/globalvars/{globalvar}/update/parameter/{parameter}', [GlobalVarsController::class, 'updateIsProtected'])
        ->name('ncms.globalvars.update.parameter');
});

Route::get('/ncms/globalvars/name/{name}', [GlobalVarsController::class, 'findByName'])
    ->name('ncms.globalvars.findByName');

Route::get('/ncms/globalvars/id/{id}', [GlobalVarsController::class, 'findById'])
    ->name('ncms.globalvars.findById');
