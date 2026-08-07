<?php

use App\Enums\WebsiteRole;
use App\Models\Layout;
use App\Models\Page;
use App\Models\User;
use App\Models\Website;

it('rejects unauthenticated access to every endpoint', function () {
    $website = Website::factory()->create();
    $page = Page::factory()->create(['website_id' => $website->id]);

    $this->getJson(route('api.v1.websites.pages.index', $website))->assertUnauthorized();
    $this->postJson(route('api.v1.websites.pages.store', $website), [])->assertUnauthorized();
    $this->getJson(route('api.v1.websites.pages.show', [$website, $page]))->assertUnauthorized();
    $this->putJson(route('api.v1.websites.pages.update', [$website, $page]), [])->assertUnauthorized();
    $this->deleteJson(route('api.v1.websites.pages.destroy', [$website, $page]))->assertUnauthorized();
});

it('lets any member view pages but denies outsiders', function () {
    $website = Website::factory()->create();
    $viewer = User::factory()->create();
    createWebsiteMembership($website, $viewer, WebsiteRole::Viewer);
    $outsider = User::factory()->create();
    $page = Page::factory()->create(['website_id' => $website->id]);

    $this->actingAs($viewer)->getJson(route('api.v1.websites.pages.index', $website))->assertSuccessful();
    $this->actingAs($viewer)->getJson(route('api.v1.websites.pages.show', [$website, $page]))->assertSuccessful();

    $this->actingAs($outsider)->getJson(route('api.v1.websites.pages.index', $website))->assertForbidden();
    $this->actingAs($outsider)->getJson(route('api.v1.websites.pages.show', [$website, $page]))->assertForbidden();
});

it('creates a page referencing a layout', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);

    $response = $this->actingAs($owner)->postJson(route('api.v1.websites.pages.store', $website), [
        'slug' => 'home',
        'layout_id' => $layout->id,
    ]);

    $response->assertCreated()
        ->assertJson(['success' => true, 'message' => 'Page created.'])
        ->assertJsonPath('data.slug', 'home')
        ->assertJsonPath('data.layout_id', $layout->id)
        ->assertJsonPath('data.status', 'active')
        ->assertJsonPath('data.is_published', false);

    $page = Page::where('slug', 'home')->first();
    expect($page->website_id)->toBe($website->id);
    expect($page->layout_id)->toBe($layout->id);
});

it('validates required fields on create', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);

    $this->actingAs($owner)
        ->postJson(route('api.v1.websites.pages.store', $website), [])
        ->assertUnprocessable()
        ->assertJson(['success' => false])
        ->assertJsonValidationErrors(['slug', 'layout_id']);
});

it('rejects a duplicate slug on the same website', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id, 'slug' => 'taken']);

    $this->actingAs($owner)
        ->postJson(route('api.v1.websites.pages.store', $website), [
            'slug' => 'taken',
            'layout_id' => $layout->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['slug']);
});

it('allows the same slug across different websites', function () {
    $websiteA = Website::factory()->create();
    $websiteB = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($websiteA, $owner, WebsiteRole::Owner);
    createWebsiteMembership($websiteB, $owner, WebsiteRole::Owner);
    $layoutA = Layout::factory()->create(['website_id' => $websiteA->id]);
    $layoutB = Layout::factory()->create(['website_id' => $websiteB->id]);
    Page::factory()->create(['website_id' => $websiteA->id, 'layout_id' => $layoutA->id, 'slug' => 'shared']);

    $this->actingAs($owner)
        ->postJson(route('api.v1.websites.pages.store', $websiteB), [
            'slug' => 'shared',
            'layout_id' => $layoutB->id,
        ])
        ->assertCreated();
});

it('rejects a layout that belongs to a different website', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $otherWebsite = Website::factory()->create();
    $foreignLayout = Layout::factory()->create(['website_id' => $otherWebsite->id]);

    $this->actingAs($owner)
        ->postJson(route('api.v1.websites.pages.store', $website), [
            'slug' => 'home',
            'layout_id' => $foreignLayout->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['layout_id']);
});

it('only allows owner, admin and editor to create pages', function (WebsiteRole $role, bool $allowed) {
    $website = Website::factory()->create();
    $actor = User::factory()->create();
    createWebsiteMembership($website, $actor, $role);
    $layout = Layout::factory()->create(['website_id' => $website->id]);

    $response = $this->actingAs($actor)->postJson(route('api.v1.websites.pages.store', $website), [
        'slug' => 'page-'.$role->value,
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

it('updates a page', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $newLayout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id, 'slug' => 'old-slug']);

    $response = $this->actingAs($owner)->putJson(
        route('api.v1.websites.pages.update', [$website, $page]),
        ['slug' => 'new-slug', 'layout_id' => $newLayout->id, 'status' => 'archived'],
    );

    $response->assertSuccessful()
        ->assertJsonPath('data.slug', 'new-slug')
        ->assertJsonPath('data.layout_id', $newLayout->id)
        ->assertJsonPath('data.status', 'archived');

    $page->refresh();
    expect($page->slug)->toBe('new-slug');
    expect($page->layout_id)->toBe($newLayout->id);
});

it('rejects updating to a slug already used on the same website', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id, 'slug' => 'taken']);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id, 'slug' => 'mine']);

    $this->actingAs($owner)
        ->putJson(route('api.v1.websites.pages.update', [$website, $page]), ['slug' => 'taken'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['slug']);
});

it('rejects updating to a layout from a different website', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);
    $otherWebsite = Website::factory()->create();
    $foreignLayout = Layout::factory()->create(['website_id' => $otherWebsite->id]);

    $this->actingAs($owner)
        ->putJson(route('api.v1.websites.pages.update', [$website, $page]), ['layout_id' => $foreignLayout->id])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['layout_id']);
});

it('only allows owner, admin and editor to update pages', function (WebsiteRole $role, bool $allowed) {
    $website = Website::factory()->create();
    $actor = User::factory()->create();
    createWebsiteMembership($website, $actor, $role);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);

    $response = $this->actingAs($actor)->putJson(
        route('api.v1.websites.pages.update', [$website, $page]),
        ['status' => 'archived'],
    );

    $allowed ? $response->assertSuccessful() : $response->assertForbidden();
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, true],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('only allows owner and admin to delete pages', function (WebsiteRole $role, bool $allowed) {
    $website = Website::factory()->create();
    $actor = User::factory()->create();
    createWebsiteMembership($website, $actor, $role);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);

    $response = $this->actingAs($actor)->deleteJson(route('api.v1.websites.pages.destroy', [$website, $page]));

    $allowed ? $response->assertSuccessful() : $response->assertForbidden();
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, false],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('soft deletes a page', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);

    $this->actingAs($owner)
        ->deleteJson(route('api.v1.websites.pages.destroy', [$website, $page]))
        ->assertSuccessful()
        ->assertJson(['success' => true, 'message' => 'Page deleted.']);

    $this->assertSoftDeleted($page);

    $this->actingAs($owner)
        ->getJson(route('api.v1.websites.pages.show', [$website, $page]))
        ->assertNotFound();
});

it('never resolves a page through a website it does not belong to', function () {
    $websiteA = Website::factory()->create();
    $websiteB = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($websiteA, $owner, WebsiteRole::Owner);
    createWebsiteMembership($websiteB, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $websiteA->id]);
    $page = Page::factory()->create(['website_id' => $websiteA->id, 'layout_id' => $layout->id]);

    $this->actingAs($owner)
        ->getJson(route('api.v1.websites.pages.show', [$websiteB, $page]))
        ->assertNotFound();

    $this->actingAs($owner)
        ->putJson(route('api.v1.websites.pages.update', [$websiteB, $page]), ['slug' => 'hijacked'])
        ->assertNotFound();

    $this->actingAs($owner)
        ->deleteJson(route('api.v1.websites.pages.destroy', [$websiteB, $page]))
        ->assertNotFound();

    expect($page->fresh()->slug)->not->toBe('hijacked');
});
