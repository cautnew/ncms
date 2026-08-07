<?php

use App\Enums\WebsiteRole;
use App\Models\Layout;
use App\Models\User;
use App\Models\Website;
use App\Policies\LayoutPolicy;

beforeEach(function () {
    $this->policy = new LayoutPolicy;
});

it('lets any member view layouts but denies an outsider', function () {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $member = User::factory()->create();
    createWebsiteMembership($website, $member, WebsiteRole::Viewer);
    $outsider = User::factory()->create();

    expect($this->policy->viewAny($member, $website))->toBeTrue();
    expect($this->policy->view($member, $layout))->toBeTrue();
    expect($this->policy->viewAny($outsider, $website))->toBeFalse();
    expect($this->policy->view($outsider, $layout))->toBeFalse();
});

it('only lets owner, admin and editor create or update layouts', function (WebsiteRole $role, bool $expected) {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $user = User::factory()->create();
    createWebsiteMembership($website, $user, $role);

    expect($this->policy->create($user, $website))->toBe($expected);
    expect($this->policy->update($user, $layout))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, true],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('only lets owner and admin delete layouts', function (WebsiteRole $role, bool $expected) {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $user = User::factory()->create();
    createWebsiteMembership($website, $user, $role);

    expect($this->policy->delete($user, $layout))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, false],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('only lets the owner restore or force-delete a layout', function (WebsiteRole $role, bool $expected) {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $user = User::factory()->create();
    createWebsiteMembership($website, $user, $role);

    expect($this->policy->restore($user, $layout))->toBe($expected);
    expect($this->policy->forceDelete($user, $layout))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, false],
]);
