<?php

use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;
use Illuminate\Support\Facades\Gate;

it('access-website gate allows any member and denies an outsider', function () {
    $website = Website::factory()->create();
    $member = User::factory()->create();
    createWebsiteMembership($website, $member, WebsiteRole::Viewer);
    $outsider = User::factory()->create();

    expect(Gate::forUser($member)->allows('access-website', $website))->toBeTrue();
    expect(Gate::forUser($outsider)->allows('access-website', $website))->toBeFalse();
});

it('manage-website-settings gate allows only owner and admin', function (WebsiteRole $role, bool $expected) {
    $website = Website::factory()->create();
    $user = User::factory()->create();
    createWebsiteMembership($website, $user, $role);

    expect(Gate::forUser($user)->allows('manage-website-settings', $website))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, false],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('review-content gate allows owner, admin and qa but not editor or viewer', function (WebsiteRole $role, bool $expected) {
    $website = Website::factory()->create();
    $user = User::factory()->create();
    createWebsiteMembership($website, $user, $role);

    expect(Gate::forUser($user)->allows('review-content', $website))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'qa' => [WebsiteRole::Qa, true],
    'editor' => [WebsiteRole::Editor, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);
