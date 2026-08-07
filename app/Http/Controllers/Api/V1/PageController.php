<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Page\CreatePageData;
use App\DTOs\Page\UpdatePageData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Page\StorePageRequest;
use App\Http\Requests\Page\UpdatePageRequest;
use App\Http\Resources\PageResource;
use App\Http\Responses\ApiResponse;
use App\Models\Page;
use App\Models\Website;
use App\Services\PageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class PageController extends Controller
{
    public function __construct(
        private readonly PageService $pages,
    ) {}

    /**
     * List the website's pages.
     *
     * Page::class must be given explicitly: passing only the 'website' route
     * param would let Laravel infer the policy from Website's class (WebsitePolicy,
     * whose viewAny() is unconditionally true for any member) instead of the
     * PagePolicy::viewAny() we actually want here.
     */
    #[Authorize('viewAny', [Page::class, 'website'])]
    public function index(Website $website): JsonResponse
    {
        return ApiResponse::success(PageResource::collection($this->pages->listForWebsite($website)));
    }

    /**
     * Create a new page for the website.
     */
    #[Authorize('create', [Page::class, 'website'])]
    public function store(StorePageRequest $request, Website $website): JsonResponse
    {
        $page = $this->pages->create($website, CreatePageData::fromRequest($request));

        return ApiResponse::success(new PageResource($page), 'Page created.', 201);
    }

    /**
     * Show a single page.
     */
    #[Authorize('view', 'page')]
    public function show(Website $website, Page $page): JsonResponse
    {
        $page = $this->pages->findForWebsite($website, $page);

        return ApiResponse::success(new PageResource($page));
    }

    /**
     * Update a page.
     */
    #[Authorize('update', 'page')]
    public function update(UpdatePageRequest $request, Website $website, Page $page): JsonResponse
    {
        $page = $this->pages->update($website, $page, UpdatePageData::fromRequest($request));

        return ApiResponse::success(new PageResource($page), 'Page updated.');
    }

    /**
     * Delete (soft-delete) a page.
     */
    #[Authorize('delete', 'page')]
    public function destroy(Website $website, Page $page): JsonResponse
    {
        $this->pages->delete($website, $page);

        return ApiResponse::success(message: 'Page deleted.');
    }
}
