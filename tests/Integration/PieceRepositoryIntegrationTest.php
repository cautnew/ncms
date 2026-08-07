<?php

use App\Models\PageVersion;
use App\Models\Piece;
use App\Models\User;
use App\Repositories\Contracts\PageVersionRepositoryInterface;
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

it('delete() soft-deletes the whole subtree, not just the piece itself', function () {
    $actor = User::factory()->create();
    $this->actingAs($actor);

    $version = PageVersion::factory()->create();
    $wrapper = Piece::factory()->twoColumnsWrapper()->create(['page_version_id' => $version->id]);
    $child = Piece::factory()->paragraph()->childOf($wrapper, 'left')->create();
    $grandchild = Piece::factory()->paragraph()->childOf($child, 'left')->create();
    $sibling = Piece::factory()->paragraph()->create(['page_version_id' => $version->id]);

    app(PieceRepositoryInterface::class)->delete($wrapper);

    expect(Piece::withTrashed()->find($wrapper->id)->trashed())->toBeTrue();
    expect(Piece::withTrashed()->find($child->id)->trashed())->toBeTrue();
    expect(Piece::withTrashed()->find($grandchild->id)->trashed())->toBeTrue();
    expect(Piece::withTrashed()->find($wrapper->id)->deleted_by)->toBe($actor->id);
    expect(Piece::withTrashed()->find($child->id)->deleted_by)->toBe($actor->id);

    // Unrelated siblings are untouched.
    expect(Piece::find($sibling->id))->not->toBeNull();
});

it('deleting a page version soft-deletes every piece belonging to it', function () {
    $actor = User::factory()->create();
    $this->actingAs($actor);

    $version = PageVersion::factory()->create();
    $root = Piece::factory()->paragraph()->create(['page_version_id' => $version->id]);
    $wrapper = Piece::factory()->twoColumnsWrapper()->create(['page_version_id' => $version->id]);
    $child = Piece::factory()->paragraph()->childOf($wrapper, 'left')->create();

    app(PageVersionRepositoryInterface::class)->delete($version);

    expect($version->fresh()->trashed())->toBeTrue();
    expect(Piece::withTrashed()->find($root->id)->trashed())->toBeTrue();
    expect(Piece::withTrashed()->find($wrapper->id)->trashed())->toBeTrue();
    expect(Piece::withTrashed()->find($child->id)->trashed())->toBeTrue();
});
