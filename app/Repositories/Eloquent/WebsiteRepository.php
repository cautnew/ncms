<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\Website;
use App\Repositories\Contracts\WebsiteRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class WebsiteRepository implements WebsiteRepositoryInterface
{
    public function paginateForUser(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $user->websites()->paginate($perPage);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Website
    {
        // fresh() reloads DB-level defaults (e.g. status) that aren't present
        // in $attributes and therefore aren't set on the in-memory instance.
        return Website::create($attributes)->fresh();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Website $website, array $attributes): Website
    {
        $website->update($attributes);

        return $website->fresh();
    }

    public function delete(Website $website): void
    {
        $website->delete();
    }
}
