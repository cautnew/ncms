<?php

namespace App\Services;

use App\DTOs\Piece\CreatePieceData;
use App\DTOs\Piece\UpdatePieceData;
use App\Models\PageVersion;
use App\Models\Piece;
use App\Models\User;
use App\Repositories\Contracts\PieceRepositoryInterface;
use Illuminate\Support\Collection;

final class PieceService
{
    public function __construct(
        private readonly PieceRepositoryInterface $pieces,
        private readonly PageVersionCloningService $cloning,
    ) {}

    /**
     * @return Collection<int, Piece>
     */
    public function tree(PageVersion $version): Collection
    {
        return $this->pieces->treeForVersion($version);
    }

    public function find(Piece $piece): Piece
    {
        return $this->pieces->withDescendants($piece);
    }

    /**
     * If the target version is published, it is cloned into a new draft first
     * (the published version is never written to), and the piece is created
     * there instead — any parent_piece_id/asset_id given is remapped to its
     * equivalent in the clone.
     */
    public function create(PageVersion $version, User $actor, CreatePieceData $data): Piece
    {
        $parentPieceId = $data->parentPieceId;
        $assetId = $data->assetId;

        if ($version->status->isPublished()) {
            $clone = $this->cloning->cloneAsDraft($version, $actor);
            $version = $clone->draft;
            $parentPieceId = $clone->piece($parentPieceId);
            $assetId = $clone->asset($assetId);
        }

        $position = $data->position ?? $this->pieces->nextPosition($version, $parentPieceId);

        return $this->pieces->create($version, [
            'type' => $data->type,
            'parent_piece_id' => $parentPieceId,
            'asset_id' => $assetId,
            'slot' => $data->slot,
            'region' => $data->region,
            'content' => $data->content ?? [],
            'settings' => $data->settings ?? [],
            'position' => $position,
        ]);
    }

    /**
     * If the piece belongs to a published version, that version is cloned
     * first and the update is redirected to the piece's equivalent there —
     * the original piece (on the published version) is left untouched.
     */
    public function update(Piece $piece, User $actor, UpdatePieceData $data): Piece
    {
        $attributes = $data->toArray();

        if ($piece->pageVersion->status->isPublished()) {
            $clone = $this->cloning->cloneAsDraft($piece->pageVersion, $actor);
            $piece = Piece::findOrFail($clone->piece($piece->id));

            if (array_key_exists('parent_piece_id', $attributes)) {
                $attributes['parent_piece_id'] = $clone->piece($attributes['parent_piece_id']);
            }
            if (array_key_exists('asset_id', $attributes)) {
                $attributes['asset_id'] = $clone->asset($attributes['asset_id']);
            }
        }

        return $this->pieces->update($piece, $attributes);
    }

    /**
     * If the piece belongs to a published version, that version is cloned
     * first and the deletion is redirected to the piece's equivalent there —
     * the original piece (on the published version) is left untouched.
     */
    public function delete(Piece $piece, User $actor): void
    {
        if ($piece->pageVersion->status->isPublished()) {
            $clone = $this->cloning->cloneAsDraft($piece->pageVersion, $actor);
            $piece = Piece::findOrFail($clone->piece($piece->id));
        }

        $this->pieces->delete($piece);
    }
}
