<?php

use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteUser;

it('resolves the users who created, last updated and deleted it', function () {
    $creator = User::factory()->create();
    $updater = User::factory()->create();
    $deleter = User::factory()->create();

    $membership = WebsiteUser::factory()->create(['created_by' => $creator->id, 'updated_by' => $updater->id]);
    $membership->asActor($deleter)->delete();

    $trashed = WebsiteUser::withTrashed()->find($membership->id);

    expect($trashed->creator->is($creator))->toBeTrue();
    expect($trashed->updater->is($updater))->toBeTrue();
    expect($trashed->deleter->is($deleter))->toBeTrue();
});

it('resolves the website, the member and the inviter', function () {
    $website = Website::factory()->create();
    $member = User::factory()->create();
    $inviter = User::factory()->create();
    $membership = WebsiteUser::factory()->create([
        'website_id' => $website->id,
        'user_id' => $member->id,
        'invited_by' => $inviter->id,
    ]);

    expect($membership->website->is($website))->toBeTrue();
    expect($membership->user->is($member))->toBeTrue();
    expect($membership->invitedBy->is($inviter))->toBeTrue();
});

it('reports is_accepted based on accepted_at', function () {
    $accepted = WebsiteUser::factory()->create();
    $pending = WebsiteUser::factory()->pending()->create();

    expect($accepted->is_accepted)->toBeTrue();
    expect($pending->is_accepted)->toBeFalse();
});

it('scopes memberships by role', function () {
    $website = Website::factory()->create();
    WebsiteUser::factory()->create(['website_id' => $website->id, 'role' => WebsiteRole::Editor]);
    WebsiteUser::factory()->create(['website_id' => $website->id, 'role' => WebsiteRole::Viewer]);

    expect(WebsiteUser::query()->role(WebsiteRole::Editor)->where('website_id', $website->id)->count())->toBe(1);
});

it('scopes memberships to only accepted ones', function () {
    $website = Website::factory()->create();
    WebsiteUser::factory()->create(['website_id' => $website->id]);
    WebsiteUser::factory()->pending()->create(['website_id' => $website->id]);

    expect(WebsiteUser::query()->accepted()->where('website_id', $website->id)->count())->toBe(1);
});
