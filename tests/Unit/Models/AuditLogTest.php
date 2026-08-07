<?php

use App\Models\AuditLog;
use App\Models\User;
use App\Models\Website;

it('belongs to a website and a user', function () {
    $website = Website::factory()->create();
    $user = User::factory()->create();
    $log = AuditLog::factory()->create(['website_id' => $website->id, 'user_id' => $user->id]);

    expect($log->website->is($website))->toBeTrue();
    expect($log->user->is($user))->toBeTrue();
});

it('resolves its auditable morph relation', function () {
    $website = Website::factory()->create();
    $log = AuditLog::factory()->forModel($website)->create();

    expect($log->auditable)->not->toBeNull();
    expect($log->auditable->is($website))->toBeTrue();
});

it('reports the acting user\'s name, or "System" when there is none', function () {
    $user = User::factory()->create(['name' => 'Jane Doe']);
    $withUser = AuditLog::factory()->create(['user_id' => $user->id]);
    $system = AuditLog::factory()->system()->create();

    expect($withUser->performed_by)->toBe('Jane Doe');
    expect($system->performed_by)->toBe('System');
});

it('scopes to a given auditable model', function () {
    // Creating the websites themselves already produces "create" AuditLog
    // rows (Website uses the Auditable trait) — assert the scope narrows to
    // *only* websiteA's rows, rather than an exact count that would be
    // coupled to how many of those auto-generated entries exist.
    $websiteA = Website::factory()->create();
    $websiteB = Website::factory()->create();
    $explicitLog = AuditLog::factory()->forModel($websiteA)->create(['action' => 'update']);
    AuditLog::factory()->forModel($websiteB)->create(['action' => 'update']);

    $results = AuditLog::query()->forAuditable(Website::class, $websiteA->id)->get();

    expect($results->pluck('auditable_id')->unique()->all())->toBe([$websiteA->id]);
    expect($results->pluck('id'))->toContain($explicitLog->id);
});

it('scopes to a given action', function () {
    AuditLog::factory()->login()->create();
    AuditLog::factory()->logout()->create();

    expect(AuditLog::query()->action('login')->count())->toBe(1);
    expect(AuditLog::query()->action('logout')->count())->toBe(1);
});

it('has no updated_at column to maintain', function () {
    expect(AuditLog::UPDATED_AT)->toBeNull();
});
