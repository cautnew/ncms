<?php

namespace App\Services;

use App\DTOs\Content\PublishedContentData;
use App\Models\Website;
use App\Repositories\Contracts\PieceRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Backs the CMS's main content-delivery endpoint. Public: no authentication,
 * no editorial state — only ever resolves a page's last published version.
 */
final class ContentService
{
    public function __construct(
        private readonly PieceRepositoryInterface $pieces,
    ) {}

    /**
     * Resolve everything needed to render a page: its website, the frozen
     * layout/seo snapshot and full piece tree of its last published version,
     * and every related asset (layout-owned and version-owned). Eager-loaded
     * in a fixed, small number of queries regardless of tree depth or asset
     * count — see the with() chain below and PieceRepository::treeForVersion().
     */
    public function findPublished(Website $website, string $slug): PublishedContentData
    {
        if ($website->status !== 'active') {
            throw (new ModelNotFoundException)->setModel(Website::class, [$website->id]);
        }

        $page = $website->pages()
            ->active()
            ->published()
            ->where('slug', $slug)
            ->with(['publishedVersion.layout.assets', 'publishedVersion.assets'])
            ->firstOrFail();

        $version = $page->publishedVersion;

        return new PublishedContentData(
            website: $website,
            page: $page,
            version: $version,
            pieces: $this->pieces->treeForVersion($version),
        );
    }
}
