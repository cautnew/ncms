<?php

use App\Enums\PieceType;
use App\Models\Asset;
use App\Models\PageVersion;
use App\Models\Piece;

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

it('orders pieces by position', function () {
    $version = PageVersion::factory()->create();
    Piece::factory()->paragraph()->create(['page_version_id' => $version->id, 'position' => 2]);
    Piece::factory()->paragraph()->create(['page_version_id' => $version->id, 'position' => 0]);
    Piece::factory()->paragraph()->create(['page_version_id' => $version->id, 'position' => 1]);

    $positions = Piece::query()->where('page_version_id', $version->id)->ordered()->pluck('position')->all();

    expect($positions)->toBe([0, 1, 2]);
});
