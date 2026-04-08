<?php

namespace App\Providers;

use App\Models\Taxonomy\Taxonomy;
use App\Models\Taxonomy\TaxonomyTerm;
use App\Observers\TaxonomyObserver;
use App\Observers\TaxonomyTermObserver;
use App\Services\Taxonomy\TaxonomyService;
use App\Services\Taxonomy\TaxonomyTermService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TaxonomyService::class);
        $this->app->singleton(TaxonomyTermService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::automaticallyEagerLoadRelationships();

        // ── Migration paths ──────────────────────────────────────
        $base = database_path('migrations');
        $this->loadMigrationsFrom([
            $base . '/admin',
            $base . '/settings',
            $base . '/users',
            $base . '/routes',
            $base . '/pages',
            $base . '/cotacao',
            $base . '/taxonomies',
        ]);

        // ── Observers ────────────────────────────────────────────
        Taxonomy::observe(TaxonomyObserver::class);
        TaxonomyTerm::observe(TaxonomyTermObserver::class);
    }
}
