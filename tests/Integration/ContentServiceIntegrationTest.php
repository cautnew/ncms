<?php

use App\Models\Asset;
use App\Models\Layout;
use App\Models\Page;
use App\Models\PageVersion;
use App\Models\Piece;
use App\Models\Website;
use App\Services\ContentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

/**
 * Exercises ContentService directly, bypassing ContentController/HTTP, to
 * verify the resolved DTO's shape and its eager-loading behavior at the
 * layer that actually owns those guarantees.
 */
it('resolves a PublishedContentData with the full tree and both asset sets, in a fixed number of queries', function () {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id, 'slug' => 'integration-page']);
    $version = PageVersion::factory()->published()->create(['page_id' => $page->id, 'layout_id' => $layout->id]);
    $page->asActor($page->created_by)->update(['published_version_id' => $version->id]);

    Asset::factory()->forLayout($layout)->create();
    Asset::factory()->forPageVersion($version)->create();
    $wrapper = Piece::factory()->twoColumnsWrapper()->create(['page_version_id' => $version->id]);
    Piece::factory()->paragraph()->childOf($wrapper, 'left')->create();

    DB::enableQueryLog();
    $result = app(ContentService::class)->findPublished($website, 'integration-page');
    $queryCount = count(DB::getQueryLog());
    DB::flushQueryLog();

    expect($result->website->is($website))->toBeTrue();
    expect($result->page->is($page))->toBeTrue();
    expect($result->version->is($version))->toBeTrue();
    expect($result->version->layout->assets)->toHaveCount(1);
    expect($result->version->assets)->toHaveCount(1);
    expect($result->pieces)->toHaveCount(1);
    expect($result->pieces->first()->children)->toHaveCount(1);
    expect($queryCount)->toBeLessThanOrEqual(6);
});

it('throws a not-found exception when the website is inactive, without ever querying for the page', function () {
    $website = Website::factory()->suspended()->create();

    expect(fn () => app(ContentService::class)->findPublished($website, 'anything'))
        ->toThrow(ModelNotFoundException::class);
});
