<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        $pathsList = $this->allPathsFrom(database_path('migrations'));
        $this->loadMigrationsFrom($pathsList);
    }

    /**
     * Return all paths from a given path.
     */
    private function allPathsFrom(string $path): array
    {
        if (!is_dir($path)) {
            return [];
        }

        $paths = [$path];

        $directories = glob($path . '/*', GLOB_ONLYDIR);

        foreach ($directories as $directory) {
            if (in_array($directory, $paths)) {
                continue;
            }

            $paths[] = $directory;
            $paths = array_merge($paths, $this->allPathsFrom($directory));
        }

        return $paths;
    }
}
