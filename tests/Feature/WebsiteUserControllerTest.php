<?php

use App\Enums\WebsiteRole;
use App\Events\WebsiteUserInvited;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteUser;
use App\Notifications\WebsiteInvitationNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

it('rejects unauthenticated access to every endpoint', function () {
    $website = Website::factory()->create();
    $user = User::factory()->create();

    $this->getJson(route('api.v1.websites.users.index', $website))->assertUnauthorized();
    $this->postJson(route('api.v1.websites.users.store', $website), [])->assertUnauthorized();
    $this->putJson(route('api.v1.websites.users.update', [$website, $user]), [])->assertUnauthorized();
    $this->deleteJson(route('api.v1.websites.users.destroy', [$website, $user]))->assertUnauthorized();
});

it('only allows owner and admin to list members', function (WebsiteRole $role, bool $allowed) {
    $website = Website::factory()->create();
    $actor = User::factory()->create();
    createWebsiteMembership($website, $actor, $role);

    $response = $this->actingAs($actor)->getJson(route('api.v1.websites.users.index', $website));

    $allowed ? $response->assertSuccessful() : $response->assertForbidden();
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, false],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('invites a brand new email, creating an account and dispatching an invitation', function () {
    Event::fake([WebsiteUserInvited::class]);

    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);

    $response = $this->actingAs($owner)->postJson(route('api.v1.websites.users.store', $website), [
        'email' => 'newmember@example.com',
        'name' => 'New Member',
        'role' => 'editor',
    ]);

    $response->assertCreated()
        ->assertJson(['success' => true, 'message' => 'User invited.'])
        ->assertJsonPath('data.role', 'editor')
        ->assertJsonPath('data.user.email', 'newmember@example.com');

    $invitee = User::where('email', 'newmember@example.com')->first();
    expect($invitee)->not->toBeNull();
    expect($invitee->name)->toBe('New Member');

    expect(WebsiteUser::query()
        ->where('website_id', $website->id)
        ->where('user_id', $invitee->id)
        ->where('role', WebsiteRole::Editor)
        ->exists())->toBeTrue();

    Event::assertDispatched(WebsiteUserInvited::class, function (WebsiteUserInvited $event) use ($invitee, $owner) {
        return $event->membership->user_id === $invitee->id && $event->inviter->is($owner);
    });
});

it('sends an email notification to the invited user', function () {
    Notification::fake();

    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);

    $this->actingAs($owner)->postJson(route('api.v1.websites.users.store', $website), [
        'email' => 'notify-me@example.com',
        'role' => 'viewer',
    ])->assertCreated();

    $invitee = User::where('email', 'notify-me@example.com')->first();

    Notification::assertSentTo($invitee, WebsiteInvitationNotification::class);
});

it('invites an existing user by email without creating a duplicate account', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $existing = User::factory()->create(['email' => 'already-here@example.com']);

    $this->actingAs($owner)->postJson(route('api.v1.websites.users.store', $website), [
        'email' => 'already-here@example.com',
        'role' => 'qa',
    ])->assertCreated();

    expect(User::where('email', 'already-here@example.com')->count())->toBe(1);
    expect(WebsiteUser::query()
        ->where('website_id', $website->id)
        ->where('user_id', $existing->id)
        ->exists())->toBeTrue();
});

it('rejects inviting an email that is already a member', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $member = User::factory()->create(['email' => 'dup@example.com']);
    createWebsiteMembership($website, $member, WebsiteRole::Viewer);

    $this->actingAs($owner)
        ->postJson(route('api.v1.websites.users.store', $website), [
            'email' => 'dup@example.com',
            'role' => 'editor',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('only allows owner and admin to invite members', function (WebsiteRole $role, bool $allowed) {
    $website = Website::factory()->create();
    $actor = User::factory()->create();
    createWebsiteMembership($website, $actor, $role);

    $response = $this->actingAs($actor)->postJson(route('api.v1.websites.users.store', $website), [
        'email' => 'someone@example.com',
        'role' => 'viewer',
    ]);

    $allowed ? $response->assertCreated() : $response->assertForbidden();
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, false],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('never lets an admin grant an owner or admin role', function (WebsiteRole $role, bool $allowed) {
    $website = Website::factory()->create();
    $admin = User::factory()->create();
    createWebsiteMembership($website, $admin, WebsiteRole::Admin);

    $response = $this->actingAs($admin)->postJson(route('api.v1.websites.users.store', $website), [
        'email' => 'someone@example.com',
        'role' => $role->value,
    ]);

    $allowed ? $response->assertCreated() : $response->assertForbidden();
})->with([
    'owner' => [WebsiteRole::Owner, false],
    'admin' => [WebsiteRole::Admin, false],
    'editor' => [WebsiteRole::Editor, true],
    'qa' => [WebsiteRole::Qa, true],
    'viewer' => [WebsiteRole::Viewer, true],
]);

it('changes a member role', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $member = User::factory()->create();
    $membership = createWebsiteMembership($website, $member, WebsiteRole::Viewer);

    $response = $this->actingAs($owner)->putJson(
        route('api.v1.websites.users.update', [$website, $member]),
        ['role' => 'editor'],
    );

    $response->assertSuccessful()->assertJsonPath('data.role', 'editor');
    expect($membership->fresh()->role)->toBe(WebsiteRole::Editor);
});

it('only allows owner and admin to change roles', function (WebsiteRole $role, bool $allowed) {
    $website = Website::factory()->create();
    $actor = User::factory()->create();
    createWebsiteMembership($website, $actor, $role);
    $member = User::factory()->create();
    createWebsiteMembership($website, $member, WebsiteRole::Viewer);

    $response = $this->actingAs($actor)->putJson(
        route('api.v1.websites.users.update', [$website, $member]),
        ['role' => 'editor'],
    );

    $allowed ? $response->assertSuccessful() : $response->assertForbidden();
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, false],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('never lets an admin change an owner or admin membership', function () {
    $website = Website::factory()->create();
    $admin = User::factory()->create();
    createWebsiteMembership($website, $admin, WebsiteRole::Admin);
    $otherAdmin = User::factory()->create();
    createWebsiteMembership($website, $otherAdmin, WebsiteRole::Admin);

    $this->actingAs($admin)
        ->putJson(route('api.v1.websites.users.update', [$website, $otherAdmin]), ['role' => 'viewer'])
        ->assertForbidden();
});

it('returns not found when changing the role of a non-member', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $outsider = User::factory()->create();

    $this->actingAs($owner)
        ->putJson(route('api.v1.websites.users.update', [$website, $outsider]), ['role' => 'viewer'])
        ->assertNotFound();
});

it('removes a member', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $member = User::factory()->create();
    $membership = createWebsiteMembership($website, $member, WebsiteRole::Viewer);

    $this->actingAs($owner)
        ->deleteJson(route('api.v1.websites.users.destroy', [$website, $member]))
        ->assertSuccessful()
        ->assertJson(['success' => true, 'message' => 'User removed from website.']);

    expect(WebsiteUser::query()->whereKey($membership->id)->exists())->toBeFalse();
});

it('only allows owner and admin to remove members', function (WebsiteRole $role, bool $allowed) {
    $website = Website::factory()->create();
    $actor = User::factory()->create();
    createWebsiteMembership($website, $actor, $role);
    $member = User::factory()->create();
    createWebsiteMembership($website, $member, WebsiteRole::Viewer);

    $response = $this->actingAs($actor)->deleteJson(route('api.v1.websites.users.destroy', [$website, $member]));

    $allowed ? $response->assertSuccessful() : $response->assertForbidden();
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, false],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('never lets an admin remove the owner', function () {
    $website = Website::factory()->create();
    $admin = User::factory()->create();
    createWebsiteMembership($website, $admin, WebsiteRole::Admin);
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);

    $this->actingAs($admin)
        ->deleteJson(route('api.v1.websites.users.destroy', [$website, $owner]))
        ->assertForbidden();
});

it('lets an admin remove another admin', function () {
    $website = Website::factory()->create();
    $admin = User::factory()->create();
    createWebsiteMembership($website, $admin, WebsiteRole::Admin);
    $otherAdmin = User::factory()->create();
    createWebsiteMembership($website, $otherAdmin, WebsiteRole::Admin);

    $this->actingAs($admin)
        ->deleteJson(route('api.v1.websites.users.destroy', [$website, $otherAdmin]))
        ->assertSuccessful();
});
