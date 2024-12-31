<?php

use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Cache;
use PHTML\HEAD;
use PHTML\TAG;

$head = new HEAD();
  
$head->append(TAG::meta(charset: 'utf-8'));
$head->append(TAG::meta(name: 'viewport', content: 'width=device-width, initial-scale=1'));
$head->append(TAG::meta(name: 'csrf-token', content: csrf_token()));

$head->append(TAG::tagTitle(($pageTitle ?? 'First Layout') . " - NCMS"));

$head->append(app(Vite::class)(['resources/css/app.css', 'resources/js/app.js']));

return $head;
