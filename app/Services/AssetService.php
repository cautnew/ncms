<?php

namespace App\Services;

use App\DTOs\Asset\UpdateAssetData;
use App\DTOs\Asset\UploadAssetData;
use App\Models\Asset;
use App\Models\PageVersion;
use App\Models\User;
use App\Models\Website;
use App\Repositories\Contracts\AssetRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

final class AssetService
{
    private const DISK = 'public';

    private const DOWNLOAD_URL_TTL_MINUTES = 5;

    public function __construct(
        private readonly AssetRepositoryInterface $assets,
        private readonly PageVersionCloningService $cloning,
    ) {}

    public function listForWebsite(Website $website, int $perPage = 15): LengthAwarePaginator
    {
        return $this->assets->paginateForWebsite($website, $perPage);
    }

    /**
     * Resolve an asset guaranteed to belong to the given website.
     */
    public function findForWebsite(Website $website, Asset $asset): Asset
    {
        return $this->assets->findForWebsite($website, $asset);
    }

    /**
     * Store the uploaded file on disk and create its database record.
     * Ownership (layout vs. page_version) has already been validated by StoreAssetRequest.
     * If the target page_version is published, it is cloned into a new draft
     * first (the published version is never written to), and the asset is
     * attached there instead.
     */
    public function upload(Website $website, User $uploader, UploadAssetData $data): Asset
    {
        $pageVersionId = $data->pageVersionId;

        if ($pageVersionId !== null) {
            $pageVersion = PageVersion::findOrFail($pageVersionId);

            if ($pageVersion->status->isPublished()) {
                $pageVersionId = $this->cloning->cloneAsDraft($pageVersion, $uploader)->draft->id;
            }
        }

        $path = $data->file->store("assets/{$website->id}", self::DISK);

        [$width, $height] = $this->extractDimensions($data->file);

        $metadata = array_filter([
            'width' => $width,
            'height' => $height,
            'alt_text' => $data->altText,
        ], fn (mixed $value): bool => $value !== null) + ($data->metadata ?? []);

        return $this->assets->create([
            'website_id' => $website->id,
            'layout_id' => $data->layoutId,
            'page_version_id' => $pageVersionId,
            'disk' => self::DISK,
            'path' => $path,
            'filename' => $data->file->getClientOriginalName(),
            'mime_type' => $data->file->getMimeType(),
            'size' => $data->file->getSize(),
            'metadata' => $metadata,
            'created_by' => $uploader->id,
        ]);
    }

    /**
     * If the asset is attached to a published page_version, that version is
     * cloned first and the update is redirected to the asset's equivalent
     * there — the original asset (on the published version) is left untouched.
     */
    public function updateMetadata(Website $website, Asset $asset, User $actor, UpdateAssetData $data): Asset
    {
        $asset = $this->assets->findForWebsite($website, $asset);

        if ($asset->pageVersion !== null && $asset->pageVersion->status->isPublished()) {
            $clone = $this->cloning->cloneAsDraft($asset->pageVersion, $actor);
            $asset = Asset::findOrFail($clone->asset($asset->id));
        }

        $metadata = array_merge($asset->metadata ?? [], $data->metadataPatch());

        return $this->assets->update($asset, ['metadata' => $metadata]);
    }

    /**
     * Same clone-on-write rule as updateMetadata().
     */
    public function delete(Website $website, Asset $asset, User $actor): void
    {
        $asset = $this->assets->findForWebsite($website, $asset);

        if ($asset->pageVersion !== null && $asset->pageVersion->status->isPublished()) {
            $clone = $this->cloning->cloneAsDraft($asset->pageVersion, $actor);
            $asset = Asset::findOrFail($clone->asset($asset->id));
        }

        $this->assets->delete($asset);
    }

    /**
     * A download URL for the asset. Uses a short-lived signed URL when the
     * disk driver supports it (e.g. S3), falling back to the disk's regular
     * public URL otherwise (e.g. the local "public" disk in development).
     *
     * @return array{url: string, expires_at: string|null}
     */
    public function downloadUrl(Website $website, Asset $asset): array
    {
        $asset = $this->assets->findForWebsite($website, $asset);
        $disk = Storage::disk($asset->disk);

        try {
            $expiresAt = now()->addMinutes(self::DOWNLOAD_URL_TTL_MINUTES);

            return [
                'url' => $disk->temporaryUrl($asset->path, $expiresAt),
                'expires_at' => $expiresAt->toIso8601String(),
            ];
        } catch (Throwable) {
            return [
                'url' => $disk->url($asset->path),
                'expires_at' => null,
            ];
        }
    }

    /**
     * @return array{0: int|null, 1: int|null}
     */
    private function extractDimensions(UploadedFile $file): array
    {
        $mimeType = (string) $file->getMimeType();

        if (! Str::startsWith($mimeType, 'image/') || $mimeType === 'image/svg+xml') {
            return [null, null];
        }

        $dimensions = @getimagesize($file->getRealPath());

        return $dimensions === false ? [null, null] : [$dimensions[0], $dimensions[1]];
    }
}
