<?php

namespace App\Repositories\Contracts;

use App\Models\Asset;
use App\Models\Website;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AssetRepositoryInterface
{
    public function paginateForWebsite(Website $website, int $perPage = 15): LengthAwarePaginator;

    /**
     * Re-fetch the asset scoped to the given website, guaranteeing it actually
     * belongs to it. Throws a ModelNotFoundException otherwise.
     */
    public function findForWebsite(Website $website, Asset $asset): Asset;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Asset;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Asset $asset, array $attributes): Asset;

    public function delete(Asset $asset): void;
}
