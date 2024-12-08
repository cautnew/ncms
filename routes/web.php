<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return ['laravel' => app()->version()];
});

require __DIR__.'/asset.php';
require __DIR__.'/auth.php';
