<?php

namespace App\Repositories\Contracts;

use App\Models\PageVersion;
use App\Models\Piece;
use Illuminate\Support\Collection;

interface PieceRepositoryInterface
{
    /**
     * The full piece tree of the version: root-level pieces with every
     * descendant recursively eager-loaded onto their `children` relation,
     * fetched via a single query (see PieceRepository::buildTree()).
     *
     * @return Collection<int, Piece>
     */
    public function treeForVersion(PageVersion $version): Collection;

    /**
     * The given piece with every descendant recursively eager-loaded onto
     * its `children` relation, fetched via a single query.
     */
    public function withDescendants(Piece $piece): Piece;

    /**
     * The next sibling position under the given parent (or at the root)
     * within the version.
     */
    public function nextPosition(PageVersion $version, ?string $parentPieceId): int;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(PageVersion $version, array $attributes): Piece;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Piece $piece, array $attributes): Piece;

    /**
     * Soft-deletes the piece and its entire subtree.
     */
    public function delete(Piece $piece): void;
}
