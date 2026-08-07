<?php

use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteUser;

it('rejects unauthenticated access to every website endpoint', function () {
    $website = Website::factory()->create();

    $this->getJson(route('api.v1.websites.index'))->assertUnauthorized();
    $this->postJson(route('api.v1.websites.store'), [])->assertUnauthorized();
    $this->getJson(route('api.v1.websites.show', $website))->assertUnauthorized();
    $this->putJson(route('api.v1.websites.update', $website), [])->assertUnauthorized();
    $this->deleteJson(route('api.v1.websites.destroy', $website))->assertUnauthorized();
});

it('creates a website and makes the creator its owner', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson(route('api.v1.websites.store'), [
        'name' => 'My Site',
        'domain' => 'example.com',
        'subdomain' => 'blog',
    ]);

    $response->assertCreated()
        ->assertJson(['success' => true, 'message' => 'Website created.'])
        ->assertJsonPath('data.domain', 'example.com')
        ->assertJsonPath('data.host', 'blog.example.com');

    $websiteId = $response->json('data.id');

    expect(WebsiteUser::query()
        ->where('website_id', $websiteId)
        ->where('user_id', $user->id)
        ->where('role', WebsiteRole::Owner)
        ->exists())->toBeTrue();
});

it('accepts an explicit empty subdomain for the root domain', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson(route('api.v1.websites.store'), [
        'name' => 'Root Site',
        'domain' => 'root.com',
        'subdomain' => '',
    ]);

    $response->assertCreated()->assertJsonPath('data.host', 'root.com');
});

it('validates required fields on create', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('api.v1.websites.store'), [])
        ->assertUnprocessable()
        ->assertJson(['success' => false])
        ->assertJsonValidationErrors(['name', 'domain']);
});

it('rejects a duplicate domain and subdomain combination on create', function () {
    $user = User::factory()->create();
    Website::factory()->create(['domain' => 'example.com', 'subdomain' => 'blog']);

    $this->actingAs($user)
        ->postJson(route('api.v1.websites.store'), [
            'name' => 'Dup',
            'domain' => 'example.com',
            'subdomain' => 'blog',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['domain']);
});

it('lists only the websites the authenticated user belongs to', function () {
    $user = User::factory()->create();
    $ownWebsite = Website::factory()->create();
    WebsiteUser::factory()->create([
        'website_id' => $ownWebsite->id,
        'user_id' => $user->id,
        'role' => WebsiteRole::Owner,
    ]);

    $otherWebsite = Website::factory()->create();
    WebsiteUser::factory()->create([
        'website_id' => $otherWebsite->id,
        'user_id' => User::factory()->create()->id,
        'role' => WebsiteRole::Owner,
    ]);

    $response = $this->actingAs($user)->getJson(route('api.v1.websites.index'));

    $response->assertSuccessful();
    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.id'))->toBe($ownWebsite->id);
});

it('allows any member to view a website but denies non-members', function () {
    $website = Website::factory()->create();
    $member = User::factory()->create();
    WebsiteUser::factory()->create([
        'website_id' => $website->id,
        'user_id' => $member->id,
        'role' => WebsiteRole::Viewer,
    ]);
    $outsider = User::factory()->create();

    $this->actingAs($member)
        ->getJson(route('api.v1.websites.show', $website))
        ->assertSuccessful();

    $this->actingAs($outsider)
        ->getJson(route('api.v1.websites.show', $website))
        ->assertForbidden();
});

it('only allows owner and admin to update a website', function (WebsiteRole $role, bool $allowed) {
    $website = Website::factory()->create();
    $user = User::factory()->create();
    WebsiteUser::factory()->create([
        'website_id' => $website->id,
        'user_id' => $user->id,
        'role' => $role,
    ]);

    $response = $this->actingAs($user)->putJson(route('api.v1.websites.update', $website), [
        'name' => 'Updated Name',
    ]);

    $allowed ? $response->assertSuccessful() : $response->assertForbidden();
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, false],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('updates a website', function () {
    $website = Website::factory()->create(['name' => 'Old Name']);
    $owner = User::factory()->create();
    WebsiteUser::factory()->create([
        'website_id' => $website->id,
        'user_id' => $owner->id,
        'role' => WebsiteRole::Owner,
    ]);

    $response = $this->actingAs($owner)->putJson(route('api.v1.websites.update', $website), [
        'name' => 'New Name',
        'status' => 'suspended',
    ]);

    $response->assertSuccessful()
        ->assertJsonPath('data.name', 'New Name')
        ->assertJsonPath('data.status', 'suspended');

    expect($website->fresh()->name)->toBe('New Name');
});

it('rejects updating to a domain and subdomain already used by another website', function () {
    Website::factory()->create(['domain' => 'taken.com', 'subdomain' => '']);
    $website = Website::factory()->create(['domain' => 'mine.com', 'subdomain' => '']);
    $owner = User::factory()->create();
    WebsiteUser::factory()->create([
        'website_id' => $website->id,
        'user_id' => $owner->id,
        'role' => WebsiteRole::Owner,
    ]);

    $this->actingAs($owner)
        ->putJson(route('api.v1.websites.update', $website), ['domain' => 'taken.com'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['domain']);
});

it('only allows the owner to delete a website', function (WebsiteRole $role, bool $allowed) {
    $website = Website::factory()->create();
    $user = User::factory()->create();
    WebsiteUser::factory()->create([
        'website_id' => $website->id,
        'user_id' => $user->id,
        'role' => $role,
    ]);

    $response = $this->actingAs($user)->deleteJson(route('api.v1.websites.destroy', $website));

    $allowed ? $response->assertSuccessful() : $response->assertForbidden();
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, false],
    'editor' => [WebsiteRole::Editor, false],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('soft deletes a website and hides it from future access', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    WebsiteUser::factory()->create([
        'website_id' => $website->id,
        'user_id' => $owner->id,
        'role' => WebsiteRole::Owner,
    ]);

    $this->actingAs($owner)
        ->deleteJson(route('api.v1.websites.destroy', $website))
        ->assertSuccessful()
        ->assertJson(['success' => true, 'message' => 'Website deleted.']);

    $this->actingAs($owner)
        ->getJson(route('api.v1.websites.show', $website))
        ->assertNotFound();

    $this->assertSoftDeleted($website);
});
