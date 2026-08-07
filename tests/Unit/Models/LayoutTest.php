<?php

use App\Models\Asset;
use App\Models\Layout;
use App\Models\PageVersion;
use App\Models\Website;

it('belongs to a website', function () {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);

    expect($layout->website->is($website))->toBeTrue();
});

it('lists page versions using it', function () {
    $layout = Layout::factory()->create();
    $version = PageVersion::factory()->create(['layout_id' => $layout->id]);

    expect($layout->pageVersions()->count())->toBe(1);
    expect($layout->pageVersions()->first()->is($version))->toBeTrue();
});

it('lists assets owned directly by it', function () {
    $layout = Layout::factory()->create();
    Asset::factory()->forLayout($layout)->create();

    expect($layout->assets()->count())->toBe(1);
});

it('scopes to only the default layout of a website', function () {
    $website = Website::factory()->create();
    Layout::factory()->default()->create(['website_id' => $website->id]);
    Layout::factory()->create(['website_id' => $website->id, 'is_default' => false]);

    expect(Layout::query()->default()->where('website_id', $website->id)->count())->toBe(1);
});

it('scopes to layouts belonging to a given website', function () {
    $websiteA = Website::factory()->create();
    $websiteB = Website::factory()->create();
    Layout::factory()->create(['website_id' => $websiteA->id]);
    Layout::factory()->create(['website_id' => $websiteB->id]);

    expect(Layout::query()->forWebsite($websiteA->id)->count())->toBe(1);
});

it('scopes to layouts with a given status, including the active shortcut', function () {
    $website = Website::factory()->create();
    Layout::factory()->create(['website_id' => $website->id, 'status' => 'active']);
    Layout::factory()->draft()->create(['website_id' => $website->id]);
    Layout::factory()->archived()->create(['website_id' => $website->id]);

    expect(Layout::query()->status('draft')->where('website_id', $website->id)->count())->toBe(1);
    expect(Layout::query()->active()->where('website_id', $website->id)->count())->toBe(1);
});
