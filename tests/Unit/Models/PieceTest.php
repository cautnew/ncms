<?php

use App\Enums\PieceType;
use App\Models\Asset;
use App\Models\PageVersion;
use App\Models\Piece;
use App\Models\User;

it('resolves the users who created, last updated and deleted it', function () {
    $creator = User::factory()->create();
    $updater = User::factory()->create();
    $deleter = User::factory()->create();

    $piece = Piece::factory()->create(['created_by' => $creator->id, 'updated_by' => $updater->id]);
    $piece->asActor($deleter)->delete();

    $trashed = Piece::withTrashed()->find($piece->id);

    expect($trashed->creator->is($creator))->toBeTrue();
    expect($trashed->updater->is($updater))->toBeTrue();
    expect($trashed->deleter->is($deleter))->toBeTrue();
});

it('resolves its parent piece', function () {
    $version = PageVersion::factory()->create();
    $parent = Piece::factory()->twoColumnsWrapper()->create(['page_version_id' => $version->id]);
    $child = Piece::factory()->paragraph()->childOf($parent, 'left')->create();

    expect($child->parent->is($parent))->toBeTrue();
});

it('resolves its referenced asset', function () {
    $asset = Asset::factory()->create();
    $piece = Piece::factory()->image($asset)->create(['page_version_id' => PageVersion::factory()->create()->id]);

    expect($piece->asset->is($asset))->toBeTrue();
});

it('scopes to pieces of a given type', function () {
    $version = PageVersion::factory()->create();
    Piece::factory()->heading()->create(['page_version_id' => $version->id]);
    Piece::factory()->paragraph()->create(['page_version_id' => $version->id]);

    expect(Piece::query()->ofType(PieceType::Heading)->where('page_version_id', $version->id)->count())->toBe(1);
});

it('scopes to root pieces assigned to a given region', function () {
    $version = PageVersion::factory()->create();
    $header = Piece::factory()->paragraph()->create(['page_version_id' => $version->id, 'region' => 'header']);
    Piece::factory()->paragraph()->create(['page_version_id' => $version->id, 'region' => 'footer']);
    $wrapper = Piece::factory()->twoColumnsWrapper()->create(['page_version_id' => $version->id, 'region' => 'header']);
    // A non-root piece is never returned, even if it somehow shared the region name.
    Piece::factory()->paragraph()->childOf($wrapper, 'left')->create();

    $results = Piece::query()->where('page_version_id', $version->id)->inRegion('header')->get();

    expect($results->pluck('id')->all())->toEqualCanonicalizing([$header->id, $wrapper->id]);
});

it('orders pieces by position', function () {
    $version = PageVersion::factory()->create();
    Piece::factory()->paragraph()->create(['page_version_id' => $version->id, 'position' => 2]);
    Piece::factory()->paragraph()->create(['page_version_id' => $version->id, 'position' => 0]);
    Piece::factory()->paragraph()->create(['page_version_id' => $version->id, 'position' => 1]);

    $positions = Piece::query()->where('page_version_id', $version->id)->ordered()->pluck('position')->all();

    expect($positions)->toBe([0, 1, 2]);
});
