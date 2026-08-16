<?php

namespace App\Repositories\Contracts;

use App\Enums\HttpMethod;
use App\Models\Route;
use App\Models\Website;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RouteRepositoryInterface
{
    public function paginateForWebsite(Website $website, int $perPage = 15): LengthAwarePaginator;

    /**
     * Re-fetch the route scoped to the given website, guaranteeing it actually
     * belongs to it. Throws a ModelNotFoundException otherwise.
     */
    public function findForWebsite(Website $website, Route $route): Route;

    /**
     * A route by id, regardless of website — used to walk redirect chains.
     */
    public function find(string $id): ?Route;

    /**
     * The single active route matching this website/path/method, if any.
     */
    public function findMatching(Website $website, string $path, HttpMethod $method): ?Route;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(Website $website, array $attributes): Route;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Route $route, array $attributes): Route;

    public function delete(Route $route): void;
}
