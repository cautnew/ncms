<?php

use App\Http\Controllers\Pages\PagesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/pages', [PagesController::class, 'index']);
