<?php

use App\Enums\WebsiteRole;
use App\Models\Asset;
use App\Models\AuditLog;
use App\Models\PageVersion;
use App\Models\User;
use App\Models\Website;

it('lists its website memberships and the websites reached through them', function () {
    $website = Website::factory()->create();
    $user = User::factory()->create();
    createWebsiteMembership($website, $user, WebsiteRole::Admin);

    expect($user->websiteUsers()->count())->toBe(1);
    expect($user->websites()->count())->toBe(1);
    // The pivot's own columns aren't cast through WebsiteUser's enum cast —
    // only the dedicated WebsiteUser model (used by roleOn(), etc.) is.
    expect($user->websites()->first()->pivot->role)->toBe(WebsiteRole::Admin->value);
});

it('lists assets it uploaded', function () {
    $user = User::factory()->create();
    Asset::factory()->create(['uploaded_by' => $user->id]);

    expect($user->uploadedAssets()->count())->toBe(1);
});

it('lists page versions it created, reviewed as qa, and published', function () {
    $creator = User::factory()->create();
    $qa = User::factory()->create();
    $publisher = User::factory()->create();

    PageVersion::factory()->published()->create([
        'created_by' => $creator->id,
        'qa_user_id' => $qa->id,
        'published_by' => $publisher->id,
    ]);

    expect($creator->createdPageVersions()->count())->toBe(1);
    expect($qa->qaReviewedPageVersions()->count())->toBe(1);
    expect($publisher->publishedPageVersions()->count())->toBe(1);
});

it('lists audit log entries it authored', function () {
    $user = User::factory()->create();
    AuditLog::factory()->create(['user_id' => $user->id]);

    expect($user->auditLogs()->count())->toBe(1);
});

it('scopes to only verified users', function () {
    User::factory()->create(['email_verified_at' => now()]);
    User::factory()->unverified()->create();

    expect(User::query()->verified()->count())->toBe(1);
});

it('resolves the role it holds on a website, or null when not a member', function () {
    $website = Website::factory()->create();
    $member = User::factory()->create();
    createWebsiteMembership($website, $member, WebsiteRole::Qa);
    $outsider = User::factory()->create();

    expect($member->roleOn($website))->toBe(WebsiteRole::Qa);
    expect($outsider->roleOn($website))->toBeNull();
});
