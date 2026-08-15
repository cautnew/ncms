<?php

namespace App\Repositories\Eloquent;

use App\Enums\HttpMethod;
use App\Models\Route;
use App\Models\Website;
use App\Repositories\Contracts\RouteRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class RouteRepository implements RouteRepositoryInterface
{
    public function paginateForWebsite(Website $website, int $perPage = 15): LengthAwarePaginator
    {
        return $website->routes()->latest()->paginate($perPage);
    }

    public function findForWebsite(Website $website, Route $route): Route
    {
        if ($route->website_id !== $website->id) {
            throw (new ModelNotFoundException)->setModel(Route::class, [$route->id]);
        }

        return $route;
    }

    public function find(string $id): ?Route
    {
        return Route::find($id);
    }

    public function findMatching(Website $website, string $path, HttpMethod $method): ?Route
    {
        return Route::query()
            ->forWebsite($website->id)
            ->matching($path, $method)
            ->first();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(Website $website, array $attributes): Route
    {
        return $website->routes()->create($attributes)->fresh();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Route $route, array $attributes): Route
    {
        $route->update($attributes);

        return $route->fresh();
    }

    public function delete(Route $route): void
    {
        $route->delete();
    }
}
