<?php

namespace App\Repositories\Eloquent;

use App\Models\PageVersion;
use App\Models\Piece;
use App\Repositories\Contracts\PieceRepositoryInterface;
use Illuminate\Support\Collection;

final class PieceRepository implements PieceRepositoryInterface
{
    /**
     * @return Collection<int, Piece>
     */
    public function treeForVersion(PageVersion $version): Collection
    {
        $grouped = $this->groupedByParent($version->pieces()->orderBy('position')->get());

        return $this->assemble($grouped, 'root');
    }

    public function withDescendants(Piece $piece): Piece
    {
        $siblings = Piece::where('page_version_id', $piece->page_version_id)->orderBy('position')->get();
        $grouped = $this->groupedByParent($siblings);

        $piece->setRelation('children', $this->assemble($grouped, $piece->id));

        return $piece;
    }

    public function nextPosition(PageVersion $version, ?string $parentPieceId): int
    {
        $max = $version->pieces()->where('parent_piece_id', $parentPieceId)->max('position');

        return $max === null ? 0 : ((int) $max) + 1;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(PageVersion $version, array $attributes): Piece
    {
        return $version->pieces()->create($attributes)->fresh();
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Piece $piece, array $attributes): Piece
    {
        $piece->update($attributes);

        return $piece->fresh();
    }

    public function delete(Piece $piece): void
    {
        $piece->delete();
    }

    /**
     * Groups a flat, single-query result set by parent_piece_id so the tree
     * can be assembled with O(1) lookups per node instead of re-filtering the
     * whole collection at every level of recursion. Root pieces are grouped
     * under the 'root' key since null can't be a Collection group key.
     *
     * @param  Collection<int, Piece>  $pieces
     * @return Collection<string, Collection<int, Piece>>
     */
    private function groupedByParent(Collection $pieces): Collection
    {
        return $pieces->groupBy(fn (Piece $piece): string => $piece->parent_piece_id ?? 'root');
    }

    /**
     * @param  Collection<string, Collection<int, Piece>>  $grouped
     * @return Collection<int, Piece>
     */
    private function assemble(Collection $grouped, string $key): Collection
    {
        return ($grouped->get($key) ?? collect())
            ->values()
            ->map(function (Piece $piece) use ($grouped): Piece {
                $piece->setRelation('children', $this->assemble($grouped, $piece->id));

                return $piece;
            });
    }
}
