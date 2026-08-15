<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Route\CreateRouteData;
use App\DTOs\Route\UpdateRouteData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Route\ResolveRouteRequest;
use App\Http\Requests\Route\StoreRouteRequest;
use App\Http\Requests\Route\UpdateRouteRequest;
use App\Http\Resources\RouteResolutionResource;
use App\Http\Resources\RouteResource;
use App\Http\Responses\ApiResponse;
use App\Models\Route;
use App\Models\Website;
use App\Services\RouteResolverService;
use App\Services\RouteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class RouteController extends Controller
{
    public function __construct(
        private readonly RouteService $routes,
        private readonly RouteResolverService $resolver,
    ) {}

    /**
     * List the website's routes.
     *
     * Route::class must be given explicitly: passing only the 'website' route
     * param would let Laravel infer the policy from Website's class (WebsitePolicy,
     * whose viewAny() is unconditionally true for any member) instead of the
     * RoutePolicy::viewAny() we actually want here.
     */
    #[Authorize('viewAny', [Route::class, 'website'])]
    public function index(Website $website): JsonResponse
    {
        return ApiResponse::success(RouteResource::collection($this->routes->listForWebsite($website)));
    }

    /**
     * Create a new route for the website.
     */
    #[Authorize('create', [Route::class, 'website'])]
    public function store(StoreRouteRequest $request, Website $website): JsonResponse
    {
        $route = $this->routes->create($website, $request->user(), CreateRouteData::fromRequest($request));

        return ApiResponse::success(new RouteResource($route), 'Route created.', 201);
    }

    /**
     * Show a single route.
     */
    #[Authorize('view', 'route')]
    public function show(Website $website, Route $route): JsonResponse
    {
        $route = $this->routes->findForWebsite($website, $route);

        return ApiResponse::success(new RouteResource($route));
    }

    /**
     * Update a route's path, method, status, or (for a redirect) target.
     */
    #[Authorize('update', 'route')]
    public function update(UpdateRouteRequest $request, Website $website, Route $route): JsonResponse
    {
        $route = $this->routes->update($website, $route, UpdateRouteData::fromRequest($request));

        return ApiResponse::success(new RouteResource($route), 'Route updated.');
    }

    /**
     * Delete (soft-delete) a route.
     */
    #[Authorize('delete', 'route')]
    public function destroy(Website $website, Route $route): JsonResponse
    {
        $this->routes->delete($website, $route);

        return ApiResponse::success(message: 'Route deleted.');
    }

    /**
     * Resolve a path (+ HTTP method) to whatever it serves: a page, an
     * asset, a redirect's target, or nothing. Public — a live site's
     * frontend needs this before it knows anything else about the URL.
     */
    public function resolve(ResolveRouteRequest $request, Website $website): JsonResponse
    {
        $resolution = $this->resolver->resolve($website, $request->string('path')->toString(), $request->targetMethod());

        return ApiResponse::success(new RouteResolutionResource($resolution));
    }
}
