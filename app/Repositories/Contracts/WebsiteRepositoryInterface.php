<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use App\Models\Website;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface WebsiteRepositoryInterface
{
    /**
     * Paginate the websites the given user has access to.
     */
    public function paginateForUser(User $user, int $perPage = 15): LengthAwarePaginator;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Website;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Website $website, array $attributes): Website;

    public function delete(Website $website): void;
}
