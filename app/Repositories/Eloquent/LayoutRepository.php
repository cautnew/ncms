<?php

namespace App\Repositories\Eloquent;

use App\Models\Layout;
use App\Models\Website;
use App\Repositories\Contracts\LayoutRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class LayoutRepository implements LayoutRepositoryInterface
{
    public function paginateForWebsite(Website $website, int $perPage = 15): LengthAwarePaginator
    {
        return $website->layouts()->paginate($perPage);
    }

    public function findForWebsite(Website $website, Layout $layout): Layout
    {
        if ($layout->website_id !== $website->id) {
            throw (new ModelNotFoundException)->setModel(Layout::class, [$layout->id]);
        }

        return $layout;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(Website $website, array $attributes): Layout
    {
        return $website->layouts()->create($attributes)->fresh();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Layout $layout, array $attributes): Layout
    {
        $layout->update($attributes);

        return $layout->fresh();
    }

    public function delete(Layout $layout): void
    {
        $layout->delete();
    }
}
