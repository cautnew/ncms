<?php

use App\Exceptions\MissingActingUserException;
use App\Models\User;
use App\Models\Website;
use Illuminate\Support\Facades\Auth;

/**
 * Exercises App\Models\Concerns\HasUserstamps directly through Website — a
 * plain, representative user of the trait.
 */
it('refuses to create a row with no authenticated user and no explicit actor', function () {
    expect(fn () => Website::create([
        'name' => 'No Actor Co', 'domain' => 'no-actor.test', 'subdomain' => '', 'locale' => 'pt-BR', 'status' => 'active',
    ]))->toThrow(MissingActingUserException::class);
});

it('derives created_by/updated_by from the authenticated user on create', function () {
    $user = User::factory()->create();
    Auth::login($user);

    $website = Website::create([
        'name' => 'Auth Co', 'domain' => 'auth-co.test', 'subdomain' => '', 'locale' => 'pt-BR', 'status' => 'active',
    ]);

    expect($website->created_by)->toBe($user->id);
    expect($website->updated_by)->toBe($user->id);
});

it('respects an explicitly provided created_by instead of the authenticated user', function () {
    $author = User::factory()->create();
    $bystander = User::factory()->create();
    Auth::login($bystander);

    $website = Website::create([
        'name' => 'Explicit Co', 'domain' => 'explicit-co.test', 'subdomain' => '', 'locale' => 'pt-BR', 'status' => 'active',
        'created_by' => $author->id,
    ]);

    expect($website->created_by)->toBe($author->id);
    expect($website->updated_by)->toBe($author->id);
});

it('refuses to update a row with no authenticated user and no explicit actor', function () {
    $website = Website::factory()->create();

    expect(fn () => $website->update(['name' => 'Changed']))->toThrow(MissingActingUserException::class);
});

it('stamps updated_by with the authenticated user on every update', function () {
    $creator = User::factory()->create();
    $website = Website::factory()->create(['created_by' => $creator->id]);

    $updater = User::factory()->create();
    Auth::login($updater);
    $website->update(['name' => 'Renamed']);

    expect($website->updated_by)->toBe($updater->id);
});

it('asActor() overrides Auth::id() for the next write', function () {
    $namedActor = User::factory()->create();
    $loggedInUser = User::factory()->create();
    Auth::login($loggedInUser);

    $website = Website::factory()->create();
    $website->asActor($namedActor)->update(['name' => 'Renamed via asActor']);

    expect($website->updated_by)->toBe($namedActor->id);
});

it('asActor() lets writes happen with no authenticated user at all', function () {
    $actor = User::factory()->create();
    $website = Website::factory()->create();

    $website->asActor($actor)->update(['name' => 'Renamed with no auth']);

    expect($website->updated_by)->toBe($actor->id);
});

it('refuses to delete a row with no authenticated user and no explicit actor', function () {
    $website = Website::factory()->create();

    expect(fn () => $website->delete())->toThrow(MissingActingUserException::class);
});

it('stamps deleted_at and deleted_by together when a row is soft-deleted', function () {
    $deleter = User::factory()->create();
    $website = Website::factory()->create();
    Auth::login($deleter);

    $website->delete();

    $trashed = Website::withTrashed()->find($website->id);
    expect($trashed->deleted_at)->not->toBeNull();
    expect($trashed->deleted_by)->toBe($deleter->id);
});

it('clears deleted_by when a soft-deleted row is restored', function () {
    $deleter = User::factory()->create();
    $website = Website::factory()->create();
    Auth::login($deleter);
    $website->delete();

    $restorer = User::factory()->create();
    Auth::login($restorer);
    $website->restore();

    expect($website->fresh()->deleted_by)->toBeNull();
    expect($website->fresh()->deleted_at)->toBeNull();
});
