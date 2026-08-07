<?php

use App\Enums\PageVersionStatus;
use App\Models\Asset;
use App\Models\PageVersion;
use App\Models\User;

it('resolves the user who created it', function () {
    $creator = User::factory()->create();
    $version = PageVersion::factory()->create(['created_by' => $creator->id]);

    expect($version->creator->is($creator))->toBeTrue();
});

it('resolves the QA user who reviewed it and the user who published it', function () {
    $qa = User::factory()->create();
    $publisher = User::factory()->create();
    $version = PageVersion::factory()->published()->create(['qa_user_id' => $qa->id, 'published_by' => $publisher->id]);

    expect($version->qaUser->is($qa))->toBeTrue();
    expect($version->publisher->is($publisher))->toBeTrue();
});

it('resolves the published source it was cloned from', function () {
    $source = PageVersion::factory()->published()->create(['version_number' => 1]);
    $clone = PageVersion::factory()->clonedFrom($source)->create(['version_number' => 2]);

    expect($clone->clonedFrom->is($source))->toBeTrue();
});

it('lists assets owned directly by it', function () {
    $version = PageVersion::factory()->create();
    Asset::factory()->forPageVersion($version)->create();

    expect($version->assets()->count())->toBe(1);
});

it('scopes to draft and to published versions', function () {
    $page = \App\Models\Page::factory()->create();
    PageVersion::factory()->create(['page_id' => $page->id, 'layout_id' => $page->layout_id, 'version_number' => 1]);
    PageVersion::factory()->published()->create(['page_id' => $page->id, 'layout_id' => $page->layout_id, 'version_number' => 2]);

    expect(PageVersion::query()->draft()->where('page_id', $page->id)->count())->toBe(1);
    expect(PageVersion::query()->published()->where('page_id', $page->id)->count())->toBe(1);
});

it('scopes to a specific given status', function () {
    $page = \App\Models\Page::factory()->create();
    PageVersion::factory()->rejected()->create(['page_id' => $page->id, 'layout_id' => $page->layout_id, 'version_number' => 1]);

    expect(PageVersion::query()->status(PageVersionStatus::Rejected)->where('page_id', $page->id)->count())->toBe(1);
});

it('has no website_id column of its own — reached via its page', function () {
    $version = PageVersion::factory()->create();

    expect($version->auditWebsiteId())->toBe($version->page->website_id);
});
