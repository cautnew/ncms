<?php

namespace App\Repositories\Eloquent;

use App\Models\Page;
use App\Models\PageVersion;
use App\Repositories\Contracts\PageVersionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class PageVersionRepository implements PageVersionRepositoryInterface
{
    public function paginateForPage(Page $page, int $perPage = 15): LengthAwarePaginator
    {
        return $page->versions()->latest('version_number')->paginate($perPage);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(Page $page, array $attributes): PageVersion
    {
        return DB::transaction(function () use ($page, $attributes): PageVersion {
            $nextVersionNumber = (int) $page->versions()->lockForUpdate()->max('version_number') + 1;

            return $page->versions()->create([
                ...$attributes,
                'version_number' => $nextVersionNumber,
            ])->fresh();
        });
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(PageVersion $pageVersion, array $attributes): PageVersion
    {
        $pageVersion->update($attributes);

        return $pageVersion->fresh();
    }

    public function delete(PageVersion $pageVersion): void
    {
        $pageVersion->delete();
    }
}
