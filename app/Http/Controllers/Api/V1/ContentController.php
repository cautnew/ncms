<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContentResource;
use App\Http\Responses\ApiResponse;
use App\Models\Website;
use App\Services\ContentService;
use Illuminate\Http\JsonResponse;

class ContentController extends Controller
{
    public function __construct(
        private readonly ContentService $content,
    ) {}

    /**
     * The CMS's main content-delivery endpoint: everything needed to render
     * a page's last published version in one response. Public — no
     * authentication, no editorial/draft content is ever exposed here.
     */
    public function show(Website $website, string $slug): JsonResponse
    {
        $content = $this->content->findPublished($website, $slug);

        return ApiResponse::success(new ContentResource($content));
    }
}
