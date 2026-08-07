<?php

namespace App\Repositories\Contracts;

use App\Models\Layout;
use App\Models\Website;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LayoutRepositoryInterface
{
    public function paginateForWebsite(Website $website, int $perPage = 15): LengthAwarePaginator;

    /**
     * Re-fetch the layout scoped to the given website, guaranteeing it actually
     * belongs to it. Throws a ModelNotFoundException otherwise.
     */
    public function findForWebsite(Website $website, Layout $layout): Layout;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(Website $website, array $attributes): Layout;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Layout $layout, array $attributes): Layout;

    public function delete(Layout $layout): void;
}
