<?php

use Illuminate\Support\Facades\Route;
use App\Models\EndPoints\EndPoint;

$endPoints = EndPoint::all();

foreach ($endPoints as $endPoint) {
  Route::addRoute($endPoint->method, $endPoint->route, function () use ($endPoint) {
    return response()->json([
      'name' => $endPoint->name,
      'description' => $endPoint->description,
      'method' => $endPoint->method,
      'route' => $endPoint->route
    ]);
  });
}
