<?php

namespace App\Repositories\Contracts;

use App\Models\Page;
use App\Models\PageVersion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PageVersionRepositoryInterface
{
    public function paginateForPage(Page $page, int $perPage = 15): LengthAwarePaginator;

    /**
     * Create a new version for the page, assigning the next sequential
     * version_number under a lock to avoid races between concurrent creations.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function create(Page $page, array $attributes): PageVersion;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(PageVersion $pageVersion, array $attributes): PageVersion;

    public function delete(PageVersion $pageVersion): void;
}
