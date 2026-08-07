<?php

use App\Enums\PageVersionStatus;
use App\Enums\WebsiteRole;
use App\Models\Asset;
use App\Models\Layout;
use App\Models\Page;
use App\Models\PageVersion;
use App\Models\User;
use App\Models\Website;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
});

it('rejects unauthenticated access to every endpoint', function () {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $asset = Asset::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);

    $this->getJson(route('api.v1.websites.assets.index', $website))->assertUnauthorized();
    $this->postJson(route('api.v1.websites.assets.store', $website), [])->assertUnauthorized();
    $this->getJson(route('api.v1.websites.assets.show', [$website, $asset]))->assertUnauthorized();
    $this->putJson(route('api.v1.websites.assets.update', [$website, $asset]), [])->assertUnauthorized();
    $this->deleteJson(route('api.v1.websites.assets.destroy', [$website, $asset]))->assertUnauthorized();
    $this->getJson(route('api.v1.websites.assets.download', [$website, $asset]))->assertUnauthorized();
});

it('lets any member view assets but denies outsiders', function () {
    $website = Website::factory()->create();
    $viewer = User::factory()->create();
    createWebsiteMembership($website, $viewer, WebsiteRole::Viewer);
    $outsider = User::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $asset = Asset::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);

    $this->actingAs($viewer)->getJson(route('api.v1.websites.assets.index', $website))->assertSuccessful();
    $this->actingAs($viewer)->getJson(route('api.v1.websites.assets.show', [$website, $asset]))->assertSuccessful();

    $this->actingAs($outsider)->getJson(route('api.v1.websites.assets.index', $website))->assertForbidden();
    $this->actingAs($outsider)->getJson(route('api.v1.websites.assets.show', [$website, $asset]))->assertForbidden();
});

it('uploads an image asset attached to a layout and extracts its dimensions', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);

    $file = UploadedFile::fake()->image('photo.jpg', 120, 80);

    $response = $this->actingAs($owner)->postJson(route('api.v1.websites.assets.store', $website), [
        'file' => $file,
        'layout_id' => $layout->id,
        'alt_text' => 'A photo',
    ]);

    $response->assertCreated()
        ->assertJson(['success' => true, 'message' => 'Asset uploaded.'])
        ->assertJsonPath('data.layout_id', $layout->id)
        ->assertJsonPath('data.page_version_id', null)
        ->assertJsonPath('data.filename', 'photo.jpg')
        ->assertJsonPath('data.width', 120)
        ->assertJsonPath('data.height', 80)
        ->assertJsonPath('data.is_image', true)
        ->assertJsonPath('data.alt_text', 'A photo');

    $asset = Asset::first();
    expect($asset->website_id)->toBe($website->id);
    expect($asset->uploaded_by)->toBe($owner->id);

    Storage::disk('public')->assertExists($asset->path);
});

it('uploads a non-image asset without extracting dimensions', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);

    $file = UploadedFile::fake()->create('document.pdf', 200, 'application/pdf');

    $response = $this->actingAs($owner)->postJson(route('api.v1.websites.assets.store', $website), [
        'file' => $file,
        'layout_id' => $layout->id,
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.width', null)
        ->assertJsonPath('data.height', null)
        ->assertJsonPath('data.is_image', false);
});

it('uploads an asset attached to a draft page version', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id]);
    $version = PageVersion::factory()->create([
        'page_id' => $page->id,
        'layout_id' => $layout->id,
        'created_by' => $owner->id,
        'status' => 'draft',
    ]);

    $response = $this->actingAs($owner)->postJson(route('api.v1.websites.assets.store', $website), [
        'file' => UploadedFile::fake()->image('banner.png'),
        'page_version_id' => $version->id,
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.page_version_id', $version->id)
        ->assertJsonPath('data.layout_id', null);
});

it('rejects uploads without an owner or with both owners', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);

    $this->actingAs($owner)
        ->postJson(route('api.v1.websites.assets.store', $website), [
            'file' => UploadedFile::fake()->image('photo.jpg'),
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['layout_id', 'page_version_id']);

    $page = Page::factory()->create(['website_id' => $website->id]);
    $version = PageVersion::factory()->create([
        'page_id' => $page->id,
        'layout_id' => $layout->id,
        'created_by' => $owner->id,
    ]);

    $this->actingAs($owner)
        ->postJson(route('api.v1.websites.assets.store', $website), [
            'file' => UploadedFile::fake()->image('photo.jpg'),
            'layout_id' => $layout->id,
            'page_version_id' => $version->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['layout_id', 'page_version_id']);
});

it('rejects a layout or page version that belongs to a different website', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);

    $otherWebsite = Website::factory()->create();
    $foreignLayout = Layout::factory()->create(['website_id' => $otherWebsite->id]);
    $foreignPage = Page::factory()->create(['website_id' => $otherWebsite->id]);
    $foreignLayoutForVersion = Layout::factory()->create(['website_id' => $otherWebsite->id]);
    $foreignVersion = PageVersion::factory()->create([
        'page_id' => $foreignPage->id,
        'layout_id' => $foreignLayoutForVersion->id,
        'created_by' => $owner->id,
    ]);

    $this->actingAs($owner)
        ->postJson(route('api.v1.websites.assets.store', $website), [
            'file' => UploadedFile::fake()->image('photo.jpg'),
            'layout_id' => $foreignLayout->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['layout_id']);

    $this->actingAs($owner)
        ->postJson(route('api.v1.websites.assets.store', $website), [
            'file' => UploadedFile::fake()->image('photo.jpg'),
            'page_version_id' => $foreignVersion->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['page_version_id']);
});

it('attaching a new asset to a published page version clones it into a new draft instead', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id]);
    $version = PageVersion::factory()->published()->create([
        'page_id' => $page->id,
        'layout_id' => $layout->id,
        'created_by' => $owner->id,
    ]);

    $response = $this->actingAs($owner)
        ->postJson(route('api.v1.websites.assets.store', $website), [
            'file' => UploadedFile::fake()->image('photo.jpg'),
            'page_version_id' => $version->id,
        ]);

    $response->assertCreated();

    $draftId = $response->json('data.page_version_id');
    expect($draftId)->not->toBe($version->id);

    $draft = PageVersion::find($draftId);
    expect($draft->status)->toBe(PageVersionStatus::Draft);
    expect($draft->cloned_from_id)->toBe($version->id);

    expect($version->fresh()->status)->toBe(PageVersionStatus::Published);
    expect($version->assets()->count())->toBe(0);
});

it('rejects unsupported file types', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);

    $this->actingAs($owner)
        ->postJson(route('api.v1.websites.assets.store', $website), [
            'file' => UploadedFile::fake()->create('script.exe', 10, 'application/x-msdownload'),
            'layout_id' => $layout->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['file']);
});

it('only allows owner, admin and editor to upload assets', function (WebsiteRole $role, bool $allowed) {
    $website = Website::factory()->create();
    $actor = User::factory()->create();
    createWebsiteMembership($website, $actor, $role);
    $layout = Layout::factory()->create(['website_id' => $website->id]);

    $response = $this->actingAs($actor)->postJson(route('api.v1.websites.assets.store', $website), [
        'file' => UploadedFile::fake()->image('photo.jpg'),
        'layout_id' => $layout->id,
    ]);

    $allowed ? $response->assertCreated() : $response->assertForbidden();
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, true],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('updates asset metadata without touching the file', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $asset = Asset::factory()->create([
        'website_id' => $website->id,
        'layout_id' => $layout->id,
        'alt_text' => 'Old alt',
        'path' => 'assets/original.jpg',
    ]);

    $response = $this->actingAs($owner)->putJson(
        route('api.v1.websites.assets.update', [$website, $asset]),
        ['alt_text' => 'New alt', 'metadata' => ['caption' => 'Nice']],
    );

    $response->assertSuccessful()
        ->assertJsonPath('data.alt_text', 'New alt')
        ->assertJsonPath('data.metadata.caption', 'Nice');

    $asset->refresh();
    expect($asset->alt_text)->toBe('New alt');
    expect($asset->path)->toBe('assets/original.jpg');
});

it('only allows owner, admin and editor to update asset metadata', function (WebsiteRole $role, bool $allowed) {
    $website = Website::factory()->create();
    $actor = User::factory()->create();
    createWebsiteMembership($website, $actor, $role);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $asset = Asset::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);

    $response = $this->actingAs($actor)->putJson(
        route('api.v1.websites.assets.update', [$website, $asset]),
        ['alt_text' => 'Updated'],
    );

    $allowed ? $response->assertSuccessful() : $response->assertForbidden();
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, true],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('updating an asset attached to a published page version clones it and updates the clone', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id]);
    $version = PageVersion::factory()->published()->create([
        'page_id' => $page->id,
        'layout_id' => $layout->id,
        'created_by' => $owner->id,
    ]);
    $asset = Asset::factory()->forPageVersion($version)->create(['uploaded_by' => $owner->id, 'alt_text' => 'Original']);

    $response = $this->actingAs($owner)
        ->putJson(route('api.v1.websites.assets.update', [$website, $asset]), ['alt_text' => 'Nope']);

    $response->assertSuccessful()->assertJsonPath('data.alt_text', 'Nope');

    $clonedAssetId = $response->json('data.id');
    expect($clonedAssetId)->not->toBe($asset->id);
    expect($response->json('data.page_version_id'))->not->toBe($version->id);

    expect($asset->fresh()->alt_text)->toBe('Original');
    expect($version->fresh()->status)->toBe(PageVersionStatus::Published);
});

it('deleting an asset attached to a published page version clones it and deletes the clone', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id]);
    $version = PageVersion::factory()->published()->create([
        'page_id' => $page->id,
        'layout_id' => $layout->id,
        'created_by' => $owner->id,
    ]);
    $asset = Asset::factory()->forPageVersion($version)->create(['uploaded_by' => $owner->id]);

    $this->actingAs($owner)
        ->deleteJson(route('api.v1.websites.assets.destroy', [$website, $asset]))
        ->assertSuccessful();

    // The original asset (still attached to the published version) survives untouched.
    expect(Asset::find($asset->id))->not->toBeNull();
    expect($version->fresh()->assets()->count())->toBe(1);

    // A new draft now exists, cloned from the published version, and holds no asset
    // (the clone of $asset was created there and then immediately deleted).
    $draft = PageVersion::where('cloned_from_id', $version->id)->sole();
    expect($draft->status)->toBe(PageVersionStatus::Draft);
    expect($draft->assets()->count())->toBe(0);
});

it('soft deletes an asset', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $asset = Asset::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);

    $this->actingAs($owner)
        ->deleteJson(route('api.v1.websites.assets.destroy', [$website, $asset]))
        ->assertSuccessful()
        ->assertJson(['success' => true, 'message' => 'Asset deleted.']);

    $this->assertSoftDeleted($asset);

    $this->actingAs($owner)
        ->getJson(route('api.v1.websites.assets.show', [$website, $asset]))
        ->assertNotFound();
});

it('returns a download url for an asset', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $asset = Asset::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);

    $response = $this->actingAs($owner)->getJson(route('api.v1.websites.assets.download', [$website, $asset]));

    $response->assertSuccessful()->assertJsonStructure(['data' => ['url']]);
    expect($response->json('data.url'))->toBeString()->not->toBeEmpty();
});

it('returns a temporary signed url when the disk driver supports it', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $asset = Asset::factory()->create([
        'website_id' => $website->id,
        'layout_id' => $layout->id,
        'disk' => 'public',
        'path' => 'assets/photo.jpg',
    ]);

    $mockDisk = Mockery::mock(\Illuminate\Filesystem\FilesystemAdapter::class);
    $mockDisk->shouldReceive('temporaryUrl')
        ->once()
        ->with('assets/photo.jpg', Mockery::type(\Carbon\CarbonInterface::class))
        ->andReturn('https://cdn.example.com/signed-url');
    Storage::shouldReceive('disk')->once()->with('public')->andReturn($mockDisk);

    $response = $this->actingAs($owner)->getJson(route('api.v1.websites.assets.download', [$website, $asset]));

    $response->assertSuccessful()->assertJsonPath('data.url', 'https://cdn.example.com/signed-url');
    expect($response->json('data.expires_at'))->not->toBeNull();
});

it('falls back to the disk\'s plain url when the driver does not support temporary urls', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $asset = Asset::factory()->create([
        'website_id' => $website->id,
        'layout_id' => $layout->id,
        'disk' => 'public',
        'path' => 'assets/photo.jpg',
    ]);

    $mockDisk = Mockery::mock(\Illuminate\Filesystem\FilesystemAdapter::class);
    $mockDisk->shouldReceive('temporaryUrl')->once()->andThrow(new RuntimeException('This driver does not support creating temporary URLs.'));
    $mockDisk->shouldReceive('url')->once()->with('assets/photo.jpg')->andReturn('https://kautch-beta-3.ddev.site/storage/assets/photo.jpg');
    Storage::shouldReceive('disk')->once()->with('public')->andReturn($mockDisk);

    $response = $this->actingAs($owner)->getJson(route('api.v1.websites.assets.download', [$website, $asset]));

    $response->assertSuccessful()
        ->assertJsonPath('data.url', 'https://kautch-beta-3.ddev.site/storage/assets/photo.jpg')
        ->assertJsonPath('data.expires_at', null);
});

it('never resolves an asset through a website it does not belong to', function () {
    $websiteA = Website::factory()->create();
    $websiteB = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($websiteA, $owner, WebsiteRole::Owner);
    createWebsiteMembership($websiteB, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $websiteA->id]);
    $asset = Asset::factory()->create(['website_id' => $websiteA->id, 'layout_id' => $layout->id]);

    $this->actingAs($owner)
        ->getJson(route('api.v1.websites.assets.show', [$websiteB, $asset]))
        ->assertNotFound();

    $this->actingAs($owner)
        ->putJson(route('api.v1.websites.assets.update', [$websiteB, $asset]), ['alt_text' => 'Hijacked'])
        ->assertNotFound();

    $this->actingAs($owner)
        ->deleteJson(route('api.v1.websites.assets.destroy', [$websiteB, $asset]))
        ->assertNotFound();

    $this->actingAs($owner)
        ->getJson(route('api.v1.websites.assets.download', [$websiteB, $asset]))
        ->assertNotFound();

    expect($asset->fresh()->alt_text)->not->toBe('Hijacked');
});
