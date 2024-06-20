<?php

use Core\Route\Route;

Route::get('/ncms', 'renderGet@Core\NCMS\HomeNCMS');
Route::get('/ncms/login', 'renderGet@Core\NCMS\LoginPage');
Route::get('/ncms/user', 'renderList@Core\NCMS\User\User');
Route::get('/ncms/dataset', 'renderPageList@Core\NCMS\Dataset\Dataset');
Route::get('/ncms/dataset/exists/{name}', 'existsByName@Core\NCMS\Dataset\Dataset');
Route::get('/ncms/dataset/add', 'renderPageAdd@Core\NCMS\Dataset\Dataset');
Route::get('/ncms/dataset/edit/{id}', 'renderPageEdit@Core\NCMS\Dataset\Dataset');
Route::get('/ncms/dataset/list', 'renderList@Core\NCMS\Dataset\Dataset');
Route::get('/ncms/admin/config/db', 'renderGet@Core\NCMS\Admin\Config\DB\ConfigDB');
Route::post('/ncms/admin/config/db', 'renderPost@Core\NCMS\Admin\Config\DB\ConfigDB');
Route::post('/ncms/admin/config/db/testconnection', 'testConnection@Core\NCMS\Admin\Config\DB\ConfigDB');
