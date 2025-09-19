<?php

use App\Http\Controllers\Routes\RoutesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/routes', [RoutesController::class, 'index']);
