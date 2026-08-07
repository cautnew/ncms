<?php

use App\Models\Page;
use App\Models\PageVersion;
use App\Models\User;

it('resolves the users who created, last updated and deleted it', function () {
    $creator = User::factory()->create();
    $updater = User::factory()->create();
    $deleter = User::factory()->create();

    $page = Page::factory()->create(['created_by' => $creator->id, 'updated_by' => $updater->id]);
    $page->asActor($deleter)->delete();

    $trashed = Page::withTrashed()->find($page->id);

    expect($trashed->creator->is($creator))->toBeTrue();
    expect($trashed->updater->is($updater))->toBeTrue();
    expect($trashed->deleter->is($deleter))->toBeTrue();
});

it('lists all of its versions', function () {
    $page = Page::factory()->create();
    PageVersion::factory()->create(['page_id' => $page->id, 'layout_id' => $page->layout_id, 'version_number' => 1]);
    PageVersion::factory()->create(['page_id' => $page->id, 'layout_id' => $page->layout_id, 'version_number' => 2]);

    expect($page->versions()->count())->toBe(2);
});

it('exposes the most recent draft version via draftVersion()', function () {
    $page = Page::factory()->create();
    PageVersion::factory()->create([
        'page_id' => $page->id, 'layout_id' => $page->layout_id, 'version_number' => 1, 'status' => 'draft',
    ]);
    $latestDraft = PageVersion::factory()->create([
        'page_id' => $page->id, 'layout_id' => $page->layout_id, 'version_number' => 2, 'status' => 'draft',
    ]);
    PageVersion::factory()->underReview()->create([
        'page_id' => $page->id, 'layout_id' => $page->layout_id, 'version_number' => 3,
    ]);

    expect($page->draftVersion?->id)->toBe($latestDraft->id);
});

it('exposes the most recent version regardless of status via latestVersion()', function () {
    $page = Page::factory()->create();
    PageVersion::factory()->create(['page_id' => $page->id, 'layout_id' => $page->layout_id, 'version_number' => 1]);
    $latest = PageVersion::factory()->published()->create([
        'page_id' => $page->id, 'layout_id' => $page->layout_id, 'version_number' => 2,
    ]);

    expect($page->latestVersion?->id)->toBe($latest->id);
});

it('reports is_published based on whether published_version_id is set', function () {
    $page = Page::factory()->create();
    expect($page->is_published)->toBeFalse();

    $version = PageVersion::factory()->published()->create(['page_id' => $page->id, 'layout_id' => $page->layout_id]);
    $page->asActor($page->created_by)->update(['published_version_id' => $version->id]);

    expect($page->fresh()->is_published)->toBeTrue();
});

it('scopes to active pages and to pages with a published version', function () {
    $published = Page::factory()->create();
    $version = PageVersion::factory()->published()->create(['page_id' => $published->id, 'layout_id' => $published->layout_id]);
    $published->asActor($published->created_by)->update(['published_version_id' => $version->id]);

    Page::factory()->archived()->create();
    Page::factory()->create();

    expect(Page::query()->active()->count())->toBe(2);
    expect(Page::query()->published()->count())->toBe(1);
});
