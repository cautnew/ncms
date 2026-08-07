<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Website\CreateWebsiteData;
use App\DTOs\Website\UpdateWebsiteData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Website\StoreWebsiteRequest;
use App\Http\Requests\Website\UpdateWebsiteRequest;
use App\Http\Resources\WebsiteResource;
use App\Http\Responses\ApiResponse;
use App\Models\Website;
use App\Services\WebsiteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class WebsiteController extends Controller
{
    public function __construct(
        private readonly WebsiteService $websites,
    ) {}

    /**
     * List the websites the authenticated user has access to.
     */
    #[Authorize('viewAny', Website::class)]
    public function index(Request $request): JsonResponse
    {
        $websites = $this->websites->listForUser($request->user());

        return ApiResponse::success(WebsiteResource::collection($websites));
    }

    /**
     * Create a new website. The creator becomes its owner.
     */
    #[Authorize('create', Website::class)]
    public function store(StoreWebsiteRequest $request): JsonResponse
    {
        $website = $this->websites->create($request->user(), CreateWebsiteData::fromRequest($request));

        return ApiResponse::success(new WebsiteResource($website), 'Website created.', 201);
    }

    /**
     * Show a single website.
     */
    #[Authorize('view', 'website')]
    public function show(Website $website): JsonResponse
    {
        return ApiResponse::success(new WebsiteResource($website));
    }

    /**
     * Update a website.
     */
    #[Authorize('update', 'website')]
    public function update(UpdateWebsiteRequest $request, Website $website): JsonResponse
    {
        $website = $this->websites->update($website, UpdateWebsiteData::fromRequest($request));

        return ApiResponse::success(new WebsiteResource($website), 'Website updated.');
    }

    /**
     * Delete (soft-delete) a website.
     */
    #[Authorize('delete', 'website')]
    public function destroy(Website $website): JsonResponse
    {
        $this->websites->delete($website);

        return ApiResponse::success(message: 'Website deleted.');
    }
}
