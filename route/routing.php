<?php

use Core\Route\Route;

Route::get('/', 'renderGet@Source\HomePage');
Route::get('/ncms', 'renderGet@Core\NCMS\HomeNCMS');
Route::get('/ncms/users', 'renderList@Core\NCMS\Users\User');
Route::get('/ncms/admin/config/db', 'renderGet@Core\NCMS\Admin\DB\ConfigDB');
Route::post('/ncms/admin/config/db', 'renderPost@Core\NCMS\Admin\DB\ConfigDB');
Route::post('/ncms/admin/config/db/testconnection', 'testConnection@Core\NCMS\Admin\DB\ConfigDB');

// Route::get('/ncms/admin', function() {
//   $content = require_once DC::PSOURCE . '/ncms/admin/admin.php';
//   echo $content;
// });

// Route::get('/ncms/admin/{qt}', function($qt) {
//   $content = require_once DC::PSOURCE . '/ncms/admin/admin.php';
//   echo $qt . $content;
// });
