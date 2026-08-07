<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Layout\CreateLayoutData;
use App\DTOs\Layout\UpdateLayoutData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Layout\StoreLayoutRequest;
use App\Http\Requests\Layout\UpdateLayoutRequest;
use App\Http\Resources\LayoutResource;
use App\Http\Responses\ApiResponse;
use App\Models\Layout;
use App\Models\Website;
use App\Services\LayoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class LayoutController extends Controller
{
    public function __construct(
        private readonly LayoutService $layouts,
    ) {}

    /**
     * List the website's layouts.
     *
     * Layout::class must be given explicitly: passing only the 'website' route
     * param would let Laravel infer the policy from Website's class (WebsitePolicy,
     * whose viewAny() is unconditionally true for any member) instead of the
     * LayoutPolicy::viewAny() we actually want here.
     */
    #[Authorize('viewAny', [Layout::class, 'website'])]
    public function index(Website $website): JsonResponse
    {
        return ApiResponse::success(LayoutResource::collection($this->layouts->listForWebsite($website)));
    }

    /**
     * Create a new layout for the website.
     */
    #[Authorize('create', [Layout::class, 'website'])]
    public function store(StoreLayoutRequest $request, Website $website): JsonResponse
    {
        $layout = $this->layouts->create($website, CreateLayoutData::fromRequest($request));

        return ApiResponse::success(new LayoutResource($layout), 'Layout created.', 201);
    }

    /**
     * Show a single layout.
     */
    #[Authorize('view', 'layout')]
    public function show(Website $website, Layout $layout): JsonResponse
    {
        $layout = $this->layouts->findForWebsite($website, $layout);

        return ApiResponse::success(new LayoutResource($layout));
    }

    /**
     * Update a layout.
     */
    #[Authorize('update', 'layout')]
    public function update(UpdateLayoutRequest $request, Website $website, Layout $layout): JsonResponse
    {
        $layout = $this->layouts->update($website, $layout, UpdateLayoutData::fromRequest($request));

        return ApiResponse::success(new LayoutResource($layout), 'Layout updated.');
    }

    /**
     * Delete (soft-delete) a layout.
     */
    #[Authorize('delete', 'layout')]
    public function destroy(Website $website, Layout $layout): JsonResponse
    {
        $this->layouts->delete($website, $layout);

        return ApiResponse::success(message: 'Layout deleted.');
    }
}
