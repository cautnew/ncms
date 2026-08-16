<?php

use App\Enums\WebsiteRole;
use App\Models\Route;
use App\Models\User;
use App\Models\Website;
use App\Policies\RoutePolicy;

beforeEach(function () {
    $this->policy = new RoutePolicy;
});

it('lets any member view routes but denies an outsider', function () {
    $website = Website::factory()->create();
    $route = Route::factory()->create(['website_id' => $website->id]);
    $member = User::factory()->create();
    createWebsiteMembership($website, $member, WebsiteRole::Viewer);
    $outsider = User::factory()->create();

    expect($this->policy->viewAny($member, $website))->toBeTrue();
    expect($this->policy->view($member, $route))->toBeTrue();
    expect($this->policy->viewAny($outsider, $website))->toBeFalse();
    expect($this->policy->view($outsider, $route))->toBeFalse();
});

it('only lets owner, admin and editor create or update routes', function (WebsiteRole $role, bool $expected) {
    $website = Website::factory()->create();
    $route = Route::factory()->create(['website_id' => $website->id]);
    $user = User::factory()->create();
    createWebsiteMembership($website, $user, $role);

    expect($this->policy->create($user, $website))->toBe($expected);
    expect($this->policy->update($user, $route))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, true],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('only lets owner and admin delete routes', function (WebsiteRole $role, bool $expected) {
    $website = Website::factory()->create();
    $route = Route::factory()->create(['website_id' => $website->id]);
    $user = User::factory()->create();
    createWebsiteMembership($website, $user, $role);

    expect($this->policy->delete($user, $route))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, false],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('only lets the owner restore or force-delete a route', function (WebsiteRole $role, bool $expected) {
    $website = Website::factory()->create();
    $route = Route::factory()->create(['website_id' => $website->id]);
    $user = User::factory()->create();
    createWebsiteMembership($website, $user, $role);

    expect($this->policy->restore($user, $route))->toBe($expected);
    expect($this->policy->forceDelete($user, $route))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, false],
]);
