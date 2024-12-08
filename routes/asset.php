<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/asset')->group(function () {
  Route::get('/asset/', 'AssetController@index');
  Route::get('/assets/{id}', 'AssetController@show');
  Route::post('/assets', 'AssetController@store');
  Route::put('/assets/{id}', 'AssetController@update');
  Route::delete('/assets/{id}', 'AssetController@destroy');
});
