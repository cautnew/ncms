<?php

/**
 * AUTOMATICALLY GENERATED ROUTES
 * Routes to public pages created by users.
 */

use Illuminate\Support\Facades\Route;
use App\Models\Routes\Route as RouteModel;

$routes = RouteModel::where('is_active', true)->get();

$routes->each(function ($route) {
    Route::get($route->route, function () use ($route) {
        return "Hello!<br>Page: {$route->name}";
    });
});