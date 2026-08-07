<?php

namespace App\Services;

use App\DTOs\Layout\CreateLayoutData;
use App\DTOs\Layout\UpdateLayoutData;
use App\Models\Layout;
use App\Models\Website;
use App\Repositories\Contracts\LayoutRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class LayoutService
{
    public function __construct(
        private readonly LayoutRepositoryInterface $layouts,
    ) {}

    public function listForWebsite(Website $website, int $perPage = 15): LengthAwarePaginator
    {
        return $this->layouts->paginateForWebsite($website, $perPage);
    }

    /**
     * Resolve a layout guaranteed to belong to the given website.
     */
    public function findForWebsite(Website $website, Layout $layout): Layout
    {
        return $this->layouts->findForWebsite($website, $layout);
    }

    public function create(Website $website, CreateLayoutData $data): Layout
    {
        return $this->layouts->create($website, $data->toArray());
    }

    public function update(Website $website, Layout $layout, UpdateLayoutData $data): Layout
    {
        $layout = $this->layouts->findForWebsite($website, $layout);

        return $this->layouts->update($layout, $data->toArray());
    }

    public function delete(Website $website, Layout $layout): void
    {
        $layout = $this->layouts->findForWebsite($website, $layout);

        $this->layouts->delete($layout);
    }
}
