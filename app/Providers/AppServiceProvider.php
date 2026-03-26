<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::automaticallyEagerLoadRelationships();
        $pathMigrations = database_path() . '/migrations';
        $this->loadMigrationsFrom([
            $pathMigrations . '/admin',
            $pathMigrations . '/users',
            $pathMigrations . '/routes',
            $pathMigrations . '/pages',
            $pathMigrations . '/cotacao',
        ]);
    }
}
