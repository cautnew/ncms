<?php

use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteUser;
use App\Policies\WebsiteUserPolicy;

beforeEach(function () {
    $this->policy = new WebsiteUserPolicy;
});

it('only lets admin and owner view the member list or a single membership', function (WebsiteRole $role, bool $expected) {
    $website = Website::factory()->create();
    $actor = User::factory()->create();
    createWebsiteMembership($website, $actor, $role);
    $membership = createWebsiteMembership($website, User::factory()->create(), WebsiteRole::Viewer);

    expect($this->policy->viewAny($actor, $website))->toBe($expected);
    expect($this->policy->view($actor, $membership))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, false],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('lets the owner invite a member with any role', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);

    expect($this->policy->create($owner, $website, WebsiteRole::Admin))->toBeTrue();
    expect($this->policy->create($owner, $website, WebsiteRole::Owner))->toBeTrue();
    expect($this->policy->create($owner, $website))->toBeTrue();
});

it('lets an admin invite only assignable roles, never owner/admin', function () {
    $website = Website::factory()->create();
    $admin = User::factory()->create();
    createWebsiteMembership($website, $admin, WebsiteRole::Admin);

    expect($this->policy->create($admin, $website, WebsiteRole::Editor))->toBeTrue();
    expect($this->policy->create($admin, $website, WebsiteRole::Qa))->toBeTrue();
    expect($this->policy->create($admin, $website, WebsiteRole::Viewer))->toBeTrue();
    expect($this->policy->create($admin, $website))->toBeTrue();
    expect($this->policy->create($admin, $website, WebsiteRole::Admin))->toBeFalse();
    expect($this->policy->create($admin, $website, WebsiteRole::Owner))->toBeFalse();
});

it('never lets editor, qa or viewer invite members', function (WebsiteRole $role) {
    $website = Website::factory()->create();
    $actor = User::factory()->create();
    createWebsiteMembership($website, $actor, $role);

    expect($this->policy->create($actor, $website, WebsiteRole::Viewer))->toBeFalse();
})->with([
    'editor' => [WebsiteRole::Editor],
    'qa' => [WebsiteRole::Qa],
    'viewer' => [WebsiteRole::Viewer],
]);

it('denies inviting for a user with no membership at all', function () {
    $website = Website::factory()->create();
    $outsider = User::factory()->create();

    expect($this->policy->create($outsider, $website, WebsiteRole::Viewer))->toBeFalse();
});

it('lets the owner change any membership to any role', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $adminMembership = createWebsiteMembership($website, User::factory()->create(), WebsiteRole::Admin);

    expect($this->policy->update($owner, $adminMembership, WebsiteRole::Owner))->toBeTrue();
});

it('never lets an admin touch an owner or admin membership', function (WebsiteRole $targetRole) {
    $website = Website::factory()->create();
    $admin = User::factory()->create();
    createWebsiteMembership($website, $admin, WebsiteRole::Admin);
    $targetMembership = createWebsiteMembership($website, User::factory()->create(), $targetRole);

    expect($this->policy->update($admin, $targetMembership, WebsiteRole::Viewer))->toBeFalse();
    expect($this->policy->delete($admin, $targetMembership))->toBe($targetRole !== WebsiteRole::Owner);
})->with([
    'owner' => [WebsiteRole::Owner],
    'admin' => [WebsiteRole::Admin],
]);

it('lets an admin change a non-privileged membership only to an assignable role', function () {
    $website = Website::factory()->create();
    $admin = User::factory()->create();
    createWebsiteMembership($website, $admin, WebsiteRole::Admin);
    $editorMembership = createWebsiteMembership($website, User::factory()->create(), WebsiteRole::Editor);

    expect($this->policy->update($admin, $editorMembership, WebsiteRole::Qa))->toBeTrue();
    expect($this->policy->update($admin, $editorMembership))->toBeTrue();
    expect($this->policy->update($admin, $editorMembership, WebsiteRole::Admin))->toBeFalse();
    expect($this->policy->update($admin, $editorMembership, WebsiteRole::Owner))->toBeFalse();
});

it('never lets editor, qa or viewer update memberships', function (WebsiteRole $role) {
    $website = Website::factory()->create();
    $actor = User::factory()->create();
    createWebsiteMembership($website, $actor, $role);
    $membership = createWebsiteMembership($website, User::factory()->create(), WebsiteRole::Viewer);

    expect($this->policy->update($actor, $membership, WebsiteRole::Editor))->toBeFalse();
})->with([
    'editor' => [WebsiteRole::Editor],
    'qa' => [WebsiteRole::Qa],
    'viewer' => [WebsiteRole::Viewer],
]);

it('lets the owner remove anyone, and an admin remove anyone except an owner', function () {
    $website = Website::factory()->create();
    $owner = User::factory()->create();
    createWebsiteMembership($website, $owner, WebsiteRole::Owner);
    $admin = User::factory()->create();
    createWebsiteMembership($website, $admin, WebsiteRole::Admin);
    $otherOwnerMembership = WebsiteUser::factory()->create(['website_id' => $website->id, 'role' => WebsiteRole::Owner]);
    $viewerMembership = createWebsiteMembership($website, User::factory()->create(), WebsiteRole::Viewer);

    expect($this->policy->delete($owner, $otherOwnerMembership))->toBeTrue();
    expect($this->policy->delete($admin, $otherOwnerMembership))->toBeFalse();
    expect($this->policy->delete($admin, $viewerMembership))->toBeTrue();
});

it('denies membership actions for a user with no membership at all', function () {
    $website = Website::factory()->create();
    $outsider = User::factory()->create();
    $membership = createWebsiteMembership($website, User::factory()->create(), WebsiteRole::Viewer);

    expect($this->policy->update($outsider, $membership))->toBeFalse();
    expect($this->policy->delete($outsider, $membership))->toBeFalse();
});
