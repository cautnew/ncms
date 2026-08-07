<?php

use App\Enums\PageVersionStatus;
use App\Models\Asset;
use App\Models\Layout;
use App\Models\Page;
use App\Models\PageVersion;
use App\Models\Piece;
use App\Models\Website;
use Illuminate\Support\Facades\DB;

/**
 * Creates a page with a published version and points Page::published_version_id
 * at it, mirroring what PageVersionService::publish() does in production.
 */
function publishPage(Website $website, Layout $layout, array $versionAttributes = []): array
{
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);

    $version = PageVersion::factory()->published()->create([
        'page_id' => $page->id,
        'layout_id' => $layout->id,
        'version_number' => 1,
        ...$versionAttributes,
    ]);

    $page->update(['published_version_id' => $version->id]);

    return [$page->fresh(), $version];
}

it('returns the full published content payload', function () {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create([
        'website_id' => $website->id,
        'name' => 'Landing Layout',
        'schema' => ['blocks' => ['hero']],
    ]);
    [$page, $version] = publishPage($website, $layout, [
        'data' => ['title' => 'Homepage'],
        'seo_snapshot' => ['title' => 'Welcome | Site', 'description' => 'A great site.'],
    ]);
    $layoutAsset = Asset::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);
    $versionAsset = Asset::factory()->forPageVersion($version)->create();
    $heading = Piece::factory()->heading()->create(['page_version_id' => $version->id, 'position' => 0]);
    $wrapper = Piece::factory()->twoColumnsWrapper()->create(['page_version_id' => $version->id, 'position' => 1]);
    $child = Piece::factory()->paragraph()->childOf($wrapper, 'left')->create();

    $response = $this->getJson(route('api.v1.content.show', [$website, $page->slug]));

    $response->assertSuccessful();
    $data = $response->json('data');

    expect($data['website']['id'])->toBe($website->id);
    expect($data['layout']['id'])->toBe($layout->id);
    expect($data['layout']['name'])->toBe('Landing Layout');
    expect($data['layout']['schema'])->toBe(['blocks' => ['hero']]);
    expect(collect($data['layout_assets'])->pluck('id')->all())->toBe([$layoutAsset->id]);
    expect($data['page']['id'])->toBe($page->id);
    expect($data['page']['slug'])->toBe($page->slug);
    expect($data['version']['id'])->toBe($version->id);
    expect($data['version']['data'])->toBe(['title' => 'Homepage']);
    expect(collect($data['version_assets'])->pluck('id')->all())->toBe([$versionAsset->id]);
    expect($data['seo'])->toBe(['title' => 'Welcome | Site', 'description' => 'A great site.']);

    expect($data['pieces'])->toHaveCount(2);
    expect($data['pieces'][0]['id'])->toBe($heading->id);
    expect($data['pieces'][1]['id'])->toBe($wrapper->id);
    expect($data['pieces'][1]['children'])->toHaveCount(1);
    expect($data['pieces'][1]['children'][0]['id'])->toBe($child->id);
});

it('does not require authentication', function () {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    [$page] = publishPage($website, $layout);

    $this->getJson(route('api.v1.content.show', [$website, $page->slug]))
        ->assertSuccessful();
});

it('reflects the layout as it was at publish time, not later live edits', function () {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id, 'name' => 'Original name', 'schema' => ['v' => 1]]);
    [$page] = publishPage($website, $layout);

    $layout->update(['name' => 'Changed after publish', 'schema' => ['v' => 2]]);

    $data = $this->getJson(route('api.v1.content.show', [$website, $page->slug]))->json('data');

    expect($data['layout']['name'])->toBe('Original name');
    expect($data['layout']['schema'])->toBe(['v' => 1]);
});

it('always resolves the latest published version, not an older archived one', function () {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);

    $oldVersion = PageVersion::factory()->archived()->create([
        'page_id' => $page->id,
        'layout_id' => $layout->id,
        'version_number' => 1,
        'data' => ['title' => 'Old'],
    ]);
    $newVersion = PageVersion::factory()->published()->create([
        'page_id' => $page->id,
        'layout_id' => $layout->id,
        'version_number' => 2,
        'data' => ['title' => 'New'],
    ]);
    $page->update(['published_version_id' => $newVersion->id]);

    $data = $this->getJson(route('api.v1.content.show', [$website, $page->slug]))->json('data');

    expect($data['version']['id'])->toBe($newVersion->id);
    expect($data['version']['data'])->toBe(['title' => 'New']);
});

it('returns 404 when the page has no published version yet', function () {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);
    PageVersion::factory()->create(['page_id' => $page->id, 'layout_id' => $layout->id]);

    $this->getJson(route('api.v1.content.show', [$website, $page->slug]))->assertNotFound();
});

it('returns 404 for an unknown slug', function () {
    $website = Website::factory()->create();

    $this->getJson(route('api.v1.content.show', [$website, 'does-not-exist']))->assertNotFound();
});

it('returns 404 when the page is archived, even with a published version', function () {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    [$page] = publishPage($website, $layout);
    $page->update(['status' => 'archived']);

    $this->getJson(route('api.v1.content.show', [$website, $page->slug]))->assertNotFound();
});

it('returns 404 when the website is not active', function (string $state) {
    $website = Website::factory()->{$state}()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    [$page] = publishPage($website, $layout);

    $this->getJson(route('api.v1.content.show', [$website, $page->slug]))->assertNotFound();
})->with([
    'suspended' => ['suspended'],
    'archived' => ['archived'],
]);

it('returns 404 for a non-existent website', function () {
    $this->getJson('/api/v1/content/'.\Illuminate\Support\Str::uuid().'/some-slug')->assertNotFound();
});

it('only includes assets scoped to the specific published version, not other versions of the same page', function () {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);
    $publishedVersion = PageVersion::factory()->published()->create([
        'page_id' => $page->id,
        'layout_id' => $layout->id,
        'version_number' => 1,
    ]);
    $page->update(['published_version_id' => $publishedVersion->id]);
    $draftVersion = PageVersion::factory()->create([
        'page_id' => $page->id,
        'layout_id' => $layout->id,
        'version_number' => 2,
    ]);

    $publishedAsset = Asset::factory()->forPageVersion($publishedVersion)->create();
    Asset::factory()->forPageVersion($draftVersion)->create();

    $data = $this->getJson(route('api.v1.content.show', [$website, $page->slug]))->json('data');

    expect(collect($data['version_assets'])->pluck('id')->all())->toBe([$publishedAsset->id]);
});

it('resolves everything in a fixed, small number of queries regardless of tree depth', function () {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    [$page, $version] = publishPage($website, $layout);

    Asset::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);
    Asset::factory()->forPageVersion($version)->create();

    $wrapper = Piece::factory()->twoColumnsWrapper()->create(['page_version_id' => $version->id]);
    $inner = Piece::factory()->twoColumnsWrapper()->childOf($wrapper, 'left')->create();
    Piece::factory()->paragraph()->childOf($inner, 'left')->create();
    Piece::factory()->heading()->childOf($wrapper, 'right')->create();
    Piece::factory()->paragraph()->create(['page_version_id' => $version->id]);

    DB::enableQueryLog();
    $this->getJson(route('api.v1.content.show', [$website, $page->slug]))->assertSuccessful();
    $queryCount = count(DB::getQueryLog());
    DB::flushQueryLog();

    expect($queryCount)->toBeLessThanOrEqual(8);
});
