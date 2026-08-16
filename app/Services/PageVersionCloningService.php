<?php

namespace App\Services;

use App\DTOs\PageVersion\PageVersionCloneResult;
use App\Enums\PageVersionStatus;
use App\Events\PageVersionCloned;
use App\Models\PageVersion;
use App\Models\Piece;
use App\Models\User;
use App\Repositories\Contracts\AssetRepositoryInterface;
use App\Repositories\Contracts\PageVersionRepositoryInterface;
use App\Repositories\Contracts\PieceRepositoryInterface;
use App\Support\RequestAuditContext;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Implements the copy-on-write invariant: a published PageVersion is never
 * edited in place. Any write against one (new/changed pieces, assets, seo,
 * or layout) instead clones it — full seo/layout snapshot, piece tree, and
 * page-version-owned assets — into a fresh draft, which is what actually
 * receives the change. See PageVersionService/PieceService/AssetService,
 * which all call cloneAsDraft() before writing whenever the target is published.
 */
final class PageVersionCloningService
{
    public function __construct(
        private readonly PageVersionRepositoryInterface $versions,
        private readonly PieceRepositoryInterface $pieces,
        private readonly AssetRepositoryInterface $assets,
        private readonly RequestAuditContext $auditContext,
    ) {}

    public function cloneAsDraft(PageVersion $source, User $actor): PageVersionCloneResult
    {
        $result = DB::transaction(function () use ($source, $actor): PageVersionCloneResult {
            $draft = $this->versions->create($source->page, [
                'layout_id' => $source->layout_id,
                'cloned_from_id' => $source->id,
                'layout_snapshot' => $source->layout_snapshot,
                'seo_snapshot' => $source->seo_snapshot,
                'status' => PageVersionStatus::Draft,
                'created_by' => $actor->id,
            ]);

            $assetIdMap = $this->cloneAssets($source, $draft);
            $pieceIdMap = $this->clonePieces($source, $draft, $assetIdMap, $actor);

            return new PageVersionCloneResult($draft, $pieceIdMap, $assetIdMap);
        });

        event(new PageVersionCloned(
            $source,
            $result->draft,
            $actor,
            $this->auditContext->ip(),
            $this->auditContext->userAgent(),
        ));

        return $result;
    }

    /**
     * @return array<string, string>
     */
    private function cloneAssets(PageVersion $source, PageVersion $draft): array
    {
        $map = [];

        foreach ($source->assets as $asset) {
            $clone = $this->assets->create([
                'website_id' => $asset->website_id,
                'layout_id' => null,
                'page_version_id' => $draft->id,
                'disk' => $asset->disk,
                'path' => $asset->path,
                'filename' => $asset->filename,
                'mime_type' => $asset->mime_type,
                'size' => $asset->size,
                'metadata' => $asset->metadata,
                'created_by' => $asset->created_by,
            ]);

            $map[$asset->id] = $clone->id;
        }

        return $map;
    }

    /**
     * Pieces form a tree via parent_piece_id, so parents must be inserted
     * before their children (an FK to a not-yet-existing row would fail).
     * Grouping the flat, single-query result by parent_piece_id first lets
     * the recursive walk below insert strictly root-to-leaf while still
     * touching the database only once per piece.
     *
     * @param  array<string, string>  $assetIdMap
     * @return array<string, string>
     */
    private function clonePieces(PageVersion $source, PageVersion $draft, array $assetIdMap, User $actor): array
    {
        $grouped = $source->pieces()->orderBy('position')->get()
            ->groupBy(fn (Piece $piece): string => $piece->parent_piece_id ?? 'root');

        $pieceIdMap = [];
        $this->clonePieceLevel($grouped, 'root', $draft, null, $assetIdMap, $pieceIdMap, $actor);

        return $pieceIdMap;
    }

    /**
     * @param  Collection<string, Collection<int, Piece>>  $grouped
     * @param  array<string, string>  $assetIdMap
     * @param  array<string, string>  $pieceIdMap
     */
    private function clonePieceLevel(
        Collection $grouped,
        string $parentKey,
        PageVersion $draft,
        ?string $newParentId,
        array $assetIdMap,
        array &$pieceIdMap,
        User $actor,
    ): void {
        foreach ($grouped->get($parentKey, collect()) as $piece) {
            $clone = $this->pieces->create($draft, [
                'parent_piece_id' => $newParentId,
                'asset_id' => $piece->asset_id !== null ? ($assetIdMap[$piece->asset_id] ?? null) : null,
                'type' => $piece->type,
                'slot' => $piece->slot,
                'region' => $piece->region,
                'position' => $piece->position,
                'content' => $piece->content,
                'settings' => $piece->settings,
                'created_by' => $actor->id,
            ]);

            $pieceIdMap[$piece->id] = $clone->id;

            $this->clonePieceLevel($grouped, $piece->id, $draft, $clone->id, $assetIdMap, $pieceIdMap, $actor);
        }
    }
}
