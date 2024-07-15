<?php

use Core\Route\Route;

Route::get('/ncms', 'renderGet@Core\NCMS\HomeNCMS');
Route::get('/ncms/login', 'renderGet@Core\NCMS\LoginPage');
Route::get('/ncms/user', 'renderList@Core\NCMS\User\User');

Route::get('/ncms/datasets', 'renderPage@Core\NCMS\Dataset\DatasetPage');
Route::get('/ncms/datasets/add', 'renderPageAdd@Core\NCMS\Dataset\DatasetAddPage');
Route::post('/ncms/datasets/add', 'postAdd@Core\NCMS\Dataset\DatasetAddPage');
Route::get('/ncms/datasets/list', 'renderList@Core\NCMS\Dataset\DatasetListPage');
Route::get('/ncms/datasets/list/{limit}', 'renderList@Core\NCMS\Dataset\DatasetListPage');
Route::get('/ncms/datasets/list/{limit}/{page}', 'renderList@Core\NCMS\Dataset\DatasetListPage');

Route::get('/ncms/datasets/{id}/exists', 'existsById@Core\NCMS\Dataset\DatasetController');
Route::get('/ncms/datasets/{name}/exists-by-name', 'existsByName@Core\NCMS\Dataset\Dataset');
Route::get('/ncms/datasets/{id}/edit', 'renderPageEdit@Core\NCMS\Dataset\DatasetEditPage');
Route::post('/ncms/datasets/{id}/edit', 'postEdit@Core\NCMS\Dataset\DatasetEditPage');
Route::get('/ncms/datasets/{id}/view', 'renderPageView@Core\NCMS\Dataset\DatasetViewPage');
Route::get('/ncms/datasets/{id}/delete', 'renderPageDelete@Core\NCMS\Dataset\DatasetDeletePage');
Route::get('/ncms/datasets/{id}/info', 'jsonDatasetInfo@Core\NCMS\Dataset\DatasetController');

Route::get('/ncms/datasets/controller/{controller}/check', 'checkController@Core\NCMS\Dataset\Dataset');

Route::get('/ncms/datasets/fields/{id}/edit', 'renderPageFieldsEdit@Core\NCMS\Dataset\Fields\DatasetFieldsEditPage');

Route::get('/ncms/admin', 'renderGet@Core\NCMS\Admin\Admin');

Route::get('/ncms/admin/config', 'renderGet@Core\NCMS\Admin\Config\Config');

Route::get('/ncms/admin/config/db', 'renderGet@Core\NCMS\Admin\Config\DB\ConfigDB');
Route::post('/ncms/admin/config/db', 'renderPost@Core\NCMS\Admin\Config\DB\ConfigDB');
Route::post('/ncms/admin/config/db/testconnection', 'testConnection@Core\NCMS\Admin\Config\DB\ConfigDB');

Route::get('/ncms/content', 'renderGet@Core\NCMS\Content\Content');
