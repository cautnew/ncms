<?php

namespace App\Repositories\Eloquent;

use App\Models\Asset;
use App\Models\Website;
use App\Repositories\Contracts\AssetRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class AssetRepository implements AssetRepositoryInterface
{
    public function paginateForWebsite(Website $website, int $perPage = 15): LengthAwarePaginator
    {
        return $website->assets()->paginate($perPage);
    }

    public function findForWebsite(Website $website, Asset $asset): Asset
    {
        if ($asset->website_id !== $website->id) {
            throw (new ModelNotFoundException)->setModel(Asset::class, [$asset->id]);
        }

        return $asset;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Asset
    {
        return Asset::create($attributes)->fresh();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Asset $asset, array $attributes): Asset
    {
        $asset->update($attributes);

        return $asset->fresh();
    }

    public function delete(Asset $asset): void
    {
        $asset->delete();
    }
}
