<?php

use Core\Route\Route;

Route::get('/', 'renderGet@Source\HomePage');

require_once __DIR__  . '/routing-admin.php';
