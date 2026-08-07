<?php

namespace App\Repositories\Contracts;

use App\Models\Page;
use App\Models\Website;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PageRepositoryInterface
{
    public function paginateForWebsite(Website $website, int $perPage = 15): LengthAwarePaginator;

    /**
     * Re-fetch the page scoped to the given website, guaranteeing it actually
     * belongs to it. Throws a ModelNotFoundException otherwise.
     */
    public function findForWebsite(Website $website, Page $page): Page;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(Website $website, array $attributes): Page;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Page $page, array $attributes): Page;

    public function delete(Page $page): void;
}
