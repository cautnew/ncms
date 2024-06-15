<?php

use Core\Route\Route;

Route::get('/ncms', 'renderGet@Core\NCMS\HomeNCMS');
Route::get('/ncms/login', 'renderGet@Core\NCMS\LoginPage');
Route::get('/ncms/user', 'renderList@Core\NCMS\User\User');
Route::get('/ncms/admin/config/db', 'renderGet@Core\NCMS\Admin\Config\DB\ConfigDB');
Route::post('/ncms/admin/config/db', 'renderPost@Core\NCMS\Admin\Config\DB\ConfigDB');
Route::post('/ncms/admin/config/db/testconnection', 'testConnection@Core\NCMS\Admin\Config\DB\ConfigDB');
