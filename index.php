<?php

use Boot\Constants\DirConstant as DC;
use Core\Route\Request;
use Core\Route\Route;

require_once __DIR__ . '/autoload.php';

$request = new Request();

Route::get('/', function() {
  $content = require_once DC::PSOURCE . '/admin/page-type.php';
  echo $content;
});

Route::get('/admin', function() {
  $content = require_once DC::PSOURCE . '/admin/admin.php';
  echo $content;
});

Route::get('/admin/{qt}', function($qt) {
  $content = require_once DC::PSOURCE . '/admin/admin.php';
  echo $qt . $content;
});

Route::resolve($request);
