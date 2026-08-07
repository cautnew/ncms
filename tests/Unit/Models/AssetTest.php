<?php

use App\Models\Asset;
use App\Models\Layout;
use App\Models\PageVersion;
use App\Models\Piece;
use App\Models\User;
use App\Models\Website;

it('resolves its layout, page version and uploader', function () {
    $layout = Layout::factory()->create();
    $uploader = User::factory()->create();
    $asset = Asset::factory()->forLayout($layout)->create(['uploaded_by' => $uploader->id]);

    expect($asset->layout->is($layout))->toBeTrue();
    expect($asset->pageVersion)->toBeNull();
    expect($asset->uploadedBy->is($uploader))->toBeTrue();
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
