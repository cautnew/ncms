<?php

use App\Enums\WebsiteRole;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Website;
use App\Policies\AuditLogPolicy;

beforeEach(function () {
    $this->policy = new AuditLogPolicy;
});

it('only lets an admin (or owner) view the audit trail list', function (WebsiteRole $role, bool $expected) {
    $website = Website::factory()->create();
    $user = User::factory()->create();
    createWebsiteMembership($website, $user, $role);

    expect($this->policy->viewAny($user, $website))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, false],
    'qa' => [WebsiteRole::Qa, false],
    'viewer' => [WebsiteRole::Viewer, false],
]);

it('only lets an admin (or owner) view a single website-scoped entry', function (WebsiteRole $role, bool $expected) {
    $website = Website::factory()->create();
    $user = User::factory()->create();
    createWebsiteMembership($website, $user, $role);
    $log = AuditLog::factory()->create(['website_id' => $website->id]);

    expect($this->policy->view($user, $log))->toBe($expected);
})->with([
    'owner' => [WebsiteRole::Owner, true],
    'admin' => [WebsiteRole::Admin, true],
    'editor' => [WebsiteRole::Editor, false],
]);

it('denies viewing an entry with no website scope (e.g. a login entry)', function () {
    $user = User::factory()->create();
    $log = AuditLog::factory()->login()->create();

    expect($this->policy->view($user, $log))->toBeFalse();
});

it('never allows create, update or delete — the trail is append-only', function () {
    $user = User::factory()->create();
    $log = AuditLog::factory()->create();

    expect($this->policy->create($user))->toBeFalse();
    expect($this->policy->update($user, $log))->toBeFalse();
    expect($this->policy->delete($user, $log))->toBeFalse();
});
