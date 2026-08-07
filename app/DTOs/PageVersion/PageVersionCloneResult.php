<?php

namespace App\DTOs\PageVersion;

use App\Models\PageVersion;

final readonly class PageVersionCloneResult
{
    /**
     * @param  array<string, string>  $pieceIdMap  old piece id => cloned piece id
     * @param  array<string, string>  $assetIdMap  old asset id => cloned asset id
     */
    public function __construct(
        public PageVersion $draft,
        public array $pieceIdMap,
        public array $assetIdMap,
    ) {}

    /**
     * The clone's equivalent of an original piece id. Falls back to the given
     * id unchanged when it wasn't part of the cloned tree (e.g. it belongs to
     * a different version entirely).
     */
    public function piece(?string $originalId): ?string
    {
        return $originalId === null ? null : ($this->pieceIdMap[$originalId] ?? $originalId);
    }

    /**
     * The clone's equivalent of an original asset id. Falls back to the given
     * id unchanged when it wasn't cloned (e.g. a layout-owned asset, which is
     * shared across versions rather than duplicated).
     */
    public function asset(?string $originalId): ?string
    {
        return $originalId === null ? null : ($this->assetIdMap[$originalId] ?? $originalId);
    }
}
