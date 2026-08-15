<?php

namespace App\Services;

use App\DTOs\Page\CreatePageData;
use App\DTOs\Page\UpdatePageData;
use App\Events\PageCreated;
use App\Models\Page;
use App\Models\Website;
use App\Repositories\Contracts\PageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class PageService
{
    public function __construct(
        private readonly PageRepositoryInterface $pages,
    ) {}

    public function listForWebsite(Website $website, int $perPage = 15): LengthAwarePaginator
    {
        return $this->pages->paginateForWebsite($website, $perPage);
    }

    /**
     * Resolve a page guaranteed to belong to the given website.
     */
    public function findForWebsite(Website $website, Page $page): Page
    {
        return $this->pages->findForWebsite($website, $page);
    }

    public function create(Website $website, CreatePageData $data): Page
    {
        $page = $this->pages->create($website, $data->toArray());

        event(new PageCreated($page));

        return $page;
    }

    public function update(Website $website, Page $page, UpdatePageData $data): Page
    {
        $page = $this->pages->findForWebsite($website, $page);

        return $this->pages->update($page, $data->toArray());
    }

    public function delete(Website $website, Page $page): void
    {
        $page = $this->pages->findForWebsite($website, $page);

        $this->pages->delete($page);
    }
}
