<?php

use App\Models\Asset;
use App\Models\Layout;
use App\Models\PageVersion;
use App\Models\Piece;
use App\Models\User;
use App\Models\Website;

it('resolves the users who last updated and deleted it', function () {
    $updater = User::factory()->create();
    $deleter = User::factory()->create();

    $asset = Asset::factory()->create(['updated_by' => $updater->id]);
    $asset->asActor($deleter)->delete();

    $trashed = Asset::withTrashed()->find($asset->id);

    expect($trashed->updater->is($updater))->toBeTrue();
    expect($trashed->deleter->is($deleter))->toBeTrue();
});

it('resolves its layout, page version and creator', function () {
    $layout = Layout::factory()->create();
    $uploader = User::factory()->create();
    $asset = Asset::factory()->forLayout($layout)->create(['created_by' => $uploader->id]);

    expect($asset->layout->is($layout))->toBeTrue();
    expect($asset->pageVersion)->toBeNull();
    expect($asset->creator->is($uploader))->toBeTrue();
});

it('reads alt_text/width/height from metadata rather than dedicated columns', function () {
    $asset = Asset::factory()->create(['metadata' => ['alt_text' => 'A picture', 'width' => 1024, 'height' => 768]]);

    expect($asset->alt_text)->toBe('A picture');
    expect($asset->width)->toBe(1024);
    expect($asset->height)->toBe(768);

    $withoutMetadata = Asset::factory()->create(['metadata' => []]);

    expect($withoutMetadata->alt_text)->toBeNull();
    expect($withoutMetadata->width)->toBeNull();
    expect($withoutMetadata->height)->toBeNull();
});

it('resolves pieces referencing it', function () {
    $asset = Asset::factory()->create();
    $piece = Piece::factory()->image($asset)->create(['page_version_id' => PageVersion::factory()->create()->id]);

    expect($asset->pieces()->count())->toBe(1);
    expect($asset->pieces()->first()->is($piece))->toBeTrue();
});

it('scopes to only image assets', function () {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    Asset::factory()->forLayout($layout)->create(['mime_type' => 'image/jpeg']);
    Asset::factory()->forLayout($layout)->document()->create();

    expect(Asset::query()->images()->where('website_id', $website->id)->count())->toBe(1);
});

it('scopes to assets belonging to a given website', function () {
    $websiteA = Website::factory()->create();
    $websiteB = Website::factory()->create();
    Asset::factory()->forLayout(Layout::factory()->create(['website_id' => $websiteA->id]))->create();
    Asset::factory()->forLayout(Layout::factory()->create(['website_id' => $websiteB->id]))->create();

    expect(Asset::query()->forWebsite($websiteA->id)->count())->toBe(1);
});
