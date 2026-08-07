<?php

use App\Models\PageVersion;
use App\Models\Piece;
use App\Repositories\Contracts\PieceRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Exercises PieceRepository's recursive, single-query tree assembly
 * directly, independent of PieceController/HTTP.
 */
it('assembles a deep tree from a single query', function () {
    $version = PageVersion::factory()->create();

    $level1 = Piece::factory()->twoColumnsWrapper()->create(['page_version_id' => $version->id, 'position' => 0]);
    $level2 = Piece::factory()->twoColumnsWrapper()->childOf($level1, 'left')->create();
    $level3 = Piece::factory()->paragraph()->childOf($level2, 'left')->create();
    Piece::factory()->heading()->childOf($level1, 'right')->create();

    DB::enableQueryLog();
    $tree = app(PieceRepositoryInterface::class)->treeForVersion($version);
    $queryCount = count(DB::getQueryLog());
    DB::flushQueryLog();

    expect($queryCount)->toBe(1);
    expect($tree)->toHaveCount(1);
    expect($tree->first()->id)->toBe($level1->id);
    expect($tree->first()->children)->toHaveCount(2);

    $left = $tree->first()->children->firstWhere('slot', 'left');
    expect($left->id)->toBe($level2->id);
    expect($left->children->first()->id)->toBe($level3->id);
});

it('scopes withDescendants() to only the given piece\'s own subtree', function () {
    $version = PageVersion::factory()->create();
    $wrapperA = Piece::factory()->twoColumnsWrapper()->create(['page_version_id' => $version->id, 'position' => 0]);
    $childA = Piece::factory()->paragraph()->childOf($wrapperA, 'left')->create();
    $wrapperB = Piece::factory()->twoColumnsWrapper()->create(['page_version_id' => $version->id, 'position' => 1]);
    Piece::factory()->paragraph()->childOf($wrapperB, 'left')->create();

    $result = app(PieceRepositoryInterface::class)->withDescendants($wrapperA);

    expect($result->children)->toHaveCount(1);
    expect($result->children->first()->id)->toBe($childA->id);
});

it('nextPosition() is scoped per parent and independent between siblings groups', function () {
    $version = PageVersion::factory()->create();
    $wrapper = Piece::factory()->twoColumnsWrapper()->create(['page_version_id' => $version->id]);
    Piece::factory()->paragraph()->create(['page_version_id' => $version->id, 'position' => 0]);
    Piece::factory()->paragraph()->create(['page_version_id' => $version->id, 'position' => 1]);
    Piece::factory()->paragraph()->childOf($wrapper, 'left')->create(['position' => 0]);

    $repository = app(PieceRepositoryInterface::class);

    expect($repository->nextPosition($version, null))->toBe(2);
    expect($repository->nextPosition($version, $wrapper->id))->toBe(1);
});
