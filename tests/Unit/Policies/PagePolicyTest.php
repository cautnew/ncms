<?php

use App\Enums\WebsiteRole;
use App\Models\Layout;
use App\Models\Page;
use App\Models\User;
use App\Models\Website;
use App\Policies\PagePolicy;

beforeEach(function () {
    $this->policy = new PagePolicy;
});

it('lets any member view pages but denies an outsider', function () {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);
    $member = User::factory()->create();
    createWebsiteMembership($website, $member, WebsiteRole::Viewer);
    $outsider = User::factory()->create();

    expect($this->policy->viewAny($member, $website))->toBeTrue();
    expect($this->policy->view($member, $page))->toBeTrue();
    expect($this->policy->viewAny($outsider, $website))->toBeFalse();
    expect($this->policy->view($outsider, $page))->toBeFalse();
});

it('only lets owner, admin and editor create or update pages', function (WebsiteRole $role, bool $expected) {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);
    $user = User::factory()->create();
    createWebsiteMembership($website, $user, $role);

    expect($this->policy->create($user, $website))->toBe($expected);
    expect($this->policy->update($user, $page))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, true],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('only lets owner and admin delete pages', function (WebsiteRole $role, bool $expected) {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);
    $user = User::factory()->create();
    createWebsiteMembership($website, $user, $role);

    expect($this->policy->delete($user, $page))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, false],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('only lets the owner restore or force-delete a page', function (WebsiteRole $role, bool $expected) {
    $website = Website::factory()->create();
    $layout = Layout::factory()->create(['website_id' => $website->id]);
    $page = Page::factory()->create(['website_id' => $website->id, 'layout_id' => $layout->id]);
    $user = User::factory()->create();
    createWebsiteMembership($website, $user, $role);

    expect($this->policy->restore($user, $page))->toBe($expected);
    expect($this->policy->forceDelete($user, $page))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, false],
]);
