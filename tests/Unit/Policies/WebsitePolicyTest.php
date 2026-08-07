<?php

use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;
use App\Policies\WebsitePolicy;

beforeEach(function () {
    $this->policy = new WebsitePolicy;
});

it('lets anyone view the list of websites', function () {
    expect($this->policy->viewAny(User::factory()->create()))->toBeTrue();
});

it('lets a member view the website but denies an outsider', function () {
    $website = Website::factory()->create();
    $member = User::factory()->create();
    createWebsiteMembership($website, $member, WebsiteRole::Viewer);
    $outsider = User::factory()->create();

    expect($this->policy->view($member, $website))->toBeTrue();
    expect($this->policy->view($outsider, $website))->toBeFalse();
});

it('lets any authenticated user create a website', function () {
    expect($this->policy->create(User::factory()->create()))->toBeTrue();
});

it('only lets admin and owner update the website', function (WebsiteRole $role, bool $expected) {
    $website = Website::factory()->create();
    $user = User::factory()->create();
    createWebsiteMembership($website, $user, $role);

    expect($this->policy->update($user, $website))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, false],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('only lets the owner delete, restore or force-delete the website', function (WebsiteRole $role, bool $expected) {
    $website = Website::factory()->create();
    $user = User::factory()->create();
    createWebsiteMembership($website, $user, $role);

    expect($this->policy->delete($user, $website))->toBe($expected);
    expect($this->policy->restore($user, $website))->toBe($expected);
    expect($this->policy->forceDelete($user, $website))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, false],
    'editor' => [WebsiteRole::Editor, false],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('denies delete/restore/forceDelete to a user with no membership', function () {
    $website = Website::factory()->create();
    $outsider = User::factory()->create();

    expect($this->policy->delete($outsider, $website))->toBeFalse();
    expect($this->policy->restore($outsider, $website))->toBeFalse();
    expect($this->policy->forceDelete($outsider, $website))->toBeFalse();
});
