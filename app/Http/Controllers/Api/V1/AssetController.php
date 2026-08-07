<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Asset\UpdateAssetData;
use App\DTOs\Asset\UploadAssetData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\StoreAssetRequest;
use App\Http\Requests\Asset\UpdateAssetRequest;
use App\Http\Resources\AssetResource;
use App\Http\Responses\ApiResponse;
use App\Models\Asset;
use App\Models\Website;
use App\Services\AssetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class AssetController extends Controller
{
    public function __construct(
        private readonly AssetService $assets,
    ) {}

    /**
     * List the website's assets.
     *
     * Asset::class must be given explicitly: passing only the 'website' route
     * param would let Laravel infer the policy from Website's class (WebsitePolicy,
     * whose viewAny() is unconditionally true for any member) instead of the
     * AssetPolicy::viewAny() we actually want here.
     */
    #[Authorize('viewAny', [Asset::class, 'website'])]
    public function index(Website $website): JsonResponse
    {
        return ApiResponse::success(AssetResource::collection($this->assets->listForWebsite($website)));
    }

    /**
     * Upload a new asset, attached to either a layout or a page version.
     */
    #[Authorize('create', [Asset::class, 'website'])]
    public function store(StoreAssetRequest $request, Website $website): JsonResponse
    {
        $asset = $this->assets->upload($website, $request->user(), UploadAssetData::fromRequest($request));

        return ApiResponse::success(new AssetResource($asset), 'Asset uploaded.', 201);
    }

    /**
     * Show a single asset.
     */
    #[Authorize('view', 'asset')]
    public function show(Website $website, Asset $asset): JsonResponse
    {
        $asset = $this->assets->findForWebsite($website, $asset);

        return ApiResponse::success(new AssetResource($asset));
    }

    /**
     * Update an asset's metadata (alt text, custom metadata). The file itself
     * and its owner (layout/page version) can never be changed after upload.
     */
    #[Authorize('update', 'asset')]
    public function update(UpdateAssetRequest $request, Website $website, Asset $asset): JsonResponse
    {
        $asset = $this->assets->updateMetadata($website, $asset, $request->user(), UpdateAssetData::fromRequest($request));

        return ApiResponse::success(new AssetResource($asset), 'Asset updated.');
    }

    /**
     * Delete (soft-delete) an asset.
     */
    #[Authorize('delete', 'asset')]
    public function destroy(Request $request, Website $website, Asset $asset): JsonResponse
    {
        $this->assets->delete($website, $asset, $request->user());

        return ApiResponse::success(message: 'Asset deleted.');
    }

    /**
     * Get a download URL for the asset (temporary/signed when the disk supports it).
     */
    #[Authorize('view', 'asset')]
    public function download(Website $website, Asset $asset): JsonResponse
    {
        return ApiResponse::success($this->assets->downloadUrl($website, $asset));
    }
}
