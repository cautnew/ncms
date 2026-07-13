<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\Pages\CreatePageServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    CreatePageServiceProvider::class,
];
