<?php

namespace App\Repositories\Eloquent;

use App\Models\Page;
use App\Models\Website;
use App\Repositories\Contracts\PageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class PageRepository implements PageRepositoryInterface
{
    public function paginateForWebsite(Website $website, int $perPage = 15): LengthAwarePaginator
    {
        return $website->pages()->paginate($perPage);
    }

    public function findForWebsite(Website $website, Page $page): Page
    {
        if ($page->website_id !== $website->id) {
            throw (new ModelNotFoundException)->setModel(Page::class, [$page->id]);
        }

        return $page;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(Website $website, array $attributes): Page
    {
        return $website->pages()->create($attributes)->fresh();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Page $page, array $attributes): Page
    {
        $page->update($attributes);

        return $page->fresh();
    }

    public function delete(Page $page): void
    {
        $page->delete();
    }
}
