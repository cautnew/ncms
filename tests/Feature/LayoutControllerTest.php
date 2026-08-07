<?php

use App\Enums\WebsiteRole;
use App\Models\Layout;
use App\Models\User;
use App\Models\Website;

it('rejects unauthenticated access to every endpoint', function () {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);

    $this->getJson(route('api.v1.websites.layouts.index', $website))->assertUnauthorized();
    $this->postJson(route('api.v1.websites.layouts.store', $website), [])->assertUnauthorized();
    $this->getJson(route('api.v1.websites.layouts.show', [$website, $layout]))->assertUnauthorized();
    $this->putJson(route('api.v1.websites.layouts.update', [$website, $layout]), [])->assertUnauthorized();
    $this->deleteJson(route('api.v1.websites.layouts.destroy', [$website, $layout]))->assertUnauthorized();
});

it('lets any member view layouts but denies outsiders', function () {
    $website = Website::factory()->create();
    $viewer = User::factory()->create();
    createWebsiteMembership($website, $viewer, WebsiteRole::Viewer);
    $outsider = User::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);

    $this->actingAs($viewer)->getJson(route('api.v1.websites.layouts.index', $website))->assertSuccessful();
    $this->actingAs($viewer)->getJson(route('api.v1.websites.layouts.show', [$website, $layout]))->assertSuccessful();

    $this->actingAs($outsider)->getJson(route('api.v1.websites.layouts.index', $website))->assertForbidden();
    $this->actingAs($outsider)->getJson(route('api.v1.websites.layouts.show', [$website, $layout]))->assertForbidden();
});

it('creates a layout with a JSON schema', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);

    $response = $this->actingAs($owner)->postJson(route('api.v1.websites.layouts.store', $website), [
        'name' => 'Homepage',
        'slug' => 'homepage',
        'description' => 'Main layout',
        'schema' => ['regions' => ['header', 'main', 'footer']],
        'status' => 'draft',
    ]);

    $response->assertCreated()
        ->assertJson(['success' => true, 'message' => 'Layout created.'])
        ->assertJsonPath('data.slug', 'homepage')
        ->assertJsonPath('data.status', 'draft')
        ->assertJsonPath('data.schema.regions', ['header', 'main', 'footer']);

    $layout = Layout::where('slug', 'homepage')->first();
    expect($layout)->not->toBeNull();
    expect($layout->schema)->toBe(['regions' => ['header', 'main', 'footer']]);
    expect($layout->website_id)->toBe($website->id);
});

it('defaults status to active when not provided', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);

    $response = $this->actingAs($owner)->postJson(route('api.v1.websites.layouts.store', $website), [
        'name' => 'No Status',
        'slug' => 'no-status',
    ]);

    $response->assertCreated()->assertJsonPath('data.status', 'active');
});

it('validates required fields on create', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);

    $this->actingAs($owner)
        ->postJson(route('api.v1.websites.layouts.store', $website), [])
        ->assertUnprocessable()
        ->assertJson(['success' => false])
        ->assertJsonValidationErrors(['name', 'slug']);
});

it('rejects a duplicate slug on the same website', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    Layout::factory()->create(['website_id' => $website->id, 'slug' => 'taken']);

    $this->actingAs($owner)
        ->postJson(route('api.v1.websites.layouts.store', $website), [
            'name' => 'Dup',
            'slug' => 'taken',
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
    Layout::factory()->create(['website_id' => $websiteA->id, 'slug' => 'shared-slug']);

    $this->actingAs($owner)
        ->postJson(route('api.v1.websites.layouts.store', $websiteB), [
            'name' => 'Shared',
            'slug' => 'shared-slug',
        ])
        ->assertCreated();
});

it('only allows owner, admin and editor to create layouts', function (WebsiteRole $role, bool $allowed) {
    $website = Website::factory()->create();
    $actor = User::factory()->create();
    createWebsiteMembership($website, $actor, $role);

    $response = $this->actingAs($actor)->postJson(route('api.v1.websites.layouts.store', $website), [
        'name' => 'Layout',
        'slug' => 'layout-'.$role->value,
    ]);

    $allowed ? $response->assertCreated() : $response->assertForbidden();
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, true],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('updates a layout', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id, 'name' => 'Old Name']);

    $response = $this->actingAs($owner)->putJson(
        route('api.v1.websites.layouts.update', [$website, $layout]),
        ['name' => 'New Name', 'status' => 'archived'],
    );

    $response->assertSuccessful()
        ->assertJsonPath('data.name', 'New Name')
        ->assertJsonPath('data.status', 'archived');

    expect($layout->fresh()->name)->toBe('New Name');
});

it('rejects updating to a slug already used on the same website', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    Layout::factory()->create(['website_id' => $website->id, 'slug' => 'taken']);
    $layout = Layout::factory()->create(['website_id' => $website->id, 'slug' => 'mine']);

    $this->actingAs($owner)
        ->putJson(route('api.v1.websites.layouts.update', [$website, $layout]), ['slug' => 'taken'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['slug']);
});

it('only allows owner, admin and editor to update layouts', function (WebsiteRole $role, bool $allowed) {
    $website = Website::factory()->create();
    $actor = User::factory()->create();
    createWebsiteMembership($website, $actor, $role);
    $layout = Layout::factory()->create(['website_id' => $website->id]);

    $response = $this->actingAs($actor)->putJson(
        route('api.v1.websites.layouts.update', [$website, $layout]),
        ['name' => 'Updated'],
    );

    $allowed ? $response->assertSuccessful() : $response->assertForbidden();
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, true],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('only allows owner and admin to delete layouts', function (WebsiteRole $role, bool $allowed) {
    $website = Website::factory()->create();
    $actor = User::factory()->create();
    createWebsiteMembership($website, $actor, $role);
    $layout = Layout::factory()->create(['website_id' => $website->id]);

    $response = $this->actingAs($actor)->deleteJson(route('api.v1.websites.layouts.destroy', [$website, $layout]));

    $allowed ? $response->assertSuccessful() : $response->assertForbidden();
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, false],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('soft deletes a layout', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $website->id]);

    $this->actingAs($owner)
        ->deleteJson(route('api.v1.websites.layouts.destroy', [$website, $layout]))
        ->assertSuccessful()
        ->assertJson(['success' => true, 'message' => 'Layout deleted.']);

    $this->assertSoftDeleted($layout);

    $this->actingAs($owner)
        ->getJson(route('api.v1.websites.layouts.show', [$website, $layout]))
        ->assertNotFound();
});

it('never resolves a layout through a website it does not belong to', function () {
    $websiteA = Website::factory()->create();
    $websiteB = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($websiteA, $owner, WebsiteRole::Owner);
    createWebsiteMembership($websiteB, $owner, WebsiteRole::Owner);
    $layout = Layout::factory()->create(['website_id' => $websiteA->id]);

    $this->actingAs($owner)
        ->getJson(route('api.v1.websites.layouts.show', [$websiteB, $layout]))
        ->assertNotFound();

    $this->actingAs($owner)
        ->putJson(route('api.v1.websites.layouts.update', [$websiteB, $layout]), ['name' => 'Hijacked'])
        ->assertNotFound();

    $this->actingAs($owner)
        ->deleteJson(route('api.v1.websites.layouts.destroy', [$websiteB, $layout]))
        ->assertNotFound();

    expect($layout->fresh()->name)->not->toBe('Hijacked');
});
