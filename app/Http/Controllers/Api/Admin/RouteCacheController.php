<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Routes\RouteCacheService;
use Illuminate\Http\JsonResponse;

class RouteCacheController extends Controller
{
    protected $routeCacheService;

    public function __construct(RouteCacheService $routeCacheService)
    {
        $this->routeCacheService = $routeCacheService;
    }

    /**
     * Get the current status of the route cache.
     */
    public function status(): JsonResponse
    {
        return response()->json([
            'is_cached' => $this->routeCacheService->isCached(),
            'last_cached_at' => $this->routeCacheService->getLastCachedAt(),
        ]);
    }

    /**
     * Generate the route cache via API.
     */
    public function store(): JsonResponse
    {
        $this->routeCacheService->generate();

        return response()->json([
            'message' => 'Route cache generated successfully.',
            'last_cached_at' => $this->routeCacheService->getLastCachedAt(),
        ]);
    }

    /**
     * Clear the route cache via API.
     */
    public function destroy(): JsonResponse
    {
        $this->routeCacheService->clear();

        return response()->json([
            'message' => 'Route cache cleared successfully.',
        ]);
    }
}
