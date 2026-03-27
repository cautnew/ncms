<?php

namespace App\Services\Routes;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class RouteCacheService
{
    /**
     * Generate the route cache.
     */
    public function generate(): bool
    {
        Artisan::call('route:cache');
        return true;
    }

    /**
     * Clear the route cache.
     */
    public function clear(): bool
    {
        Artisan::call('route:clear');
        return true;
    }

    /**
     * Get the last time the routes were cached.
     */
    public function getLastCachedAt(): ?string
    {
        $path = app()->getCachedRoutesPath();
        
        if (File::exists($path)) {
            return date('d/m/Y H:i:s', File::lastModified($path));
        }

        return null;
    }

    /**
     * Check if the routes are currently cached.
     */
    public function isCached(): bool
    {
        return File::exists(app()->getCachedRoutesPath());
    }
}
