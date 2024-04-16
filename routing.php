<?php

use Core\Route\Request;
use Core\Route\Route;

require_once __DIR__ . '/autoload.php';

$request = new Request();

Route::get('/', function() {
  $content = require_once 'index.php';
  echo $content;
});

Route::resolve($request);
