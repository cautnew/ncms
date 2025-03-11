<?php

namespace App\Providers\NCMS\User;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        echo UserServiceProvider::class . "register - foi";
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        echo UserServiceProvider::class . "boot - foi";
    }
}
