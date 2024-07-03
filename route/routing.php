<?php

use Core\Route\Route;

Route::get('/', 'renderGet@Source\HomePage');

$resources = require_once __DIR__ . '/resources.php';
require_once __DIR__  . '/routing-admin.php';
