<?php

use Core\Route\Request;
use Core\Route\Route;

require_once __DIR__ . '/autoload.php';

$request = new Request();

require_once __DIR__ . '/route/routing.php';

$response = Route::resolve($request);
echo $response;
