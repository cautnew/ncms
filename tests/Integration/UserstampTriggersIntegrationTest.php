<?php

use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteUser;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Exercises the DB-level triggers installed by App\Support\Database\
 * UserstampSchema directly via raw SQL, bypassing Eloquent entirely — the
 * exact scenario they exist for: a write that never goes through
 * HasUserstamps' PHP-side logic at all.
 */
it('auto-fills created_at/updated_at on insert when the application omits them', function () {
    $user = User::factory()->create();

    DB::table('websites')->insert([
        'id' => (string) Str::uuid(),
        'name' => 'Raw Insert Co',
        'domain' => 'raw-insert.test',
        'subdomain' => '',
        'locale' => 'pt-BR',
        'status' => 'active',
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    $row = DB::table('websites')->where('domain', 'raw-insert.test')->first();

    expect($row->created_at)->not->toBeNull();
    expect($row->updated_at)->not->toBeNull();
});

it('refills updated_at on a raw update that explicitly nulls it', function () {
    $website = Website::factory()->create();

    DB::table('websites')->where('id', $website->id)->update(['name' => 'Raw Renamed', 'updated_at' => null]);

    $row = DB::table('websites')->where('id', $website->id)->first();
    expect($row->updated_at)->not->toBeNull();
});

it('auto-fills deleted_at when deleted_by is set on a raw update without it', function () {
    $website = Website::factory()->create();
    $deleter = User::factory()->create();

    DB::table('websites')->where('id', $website->id)->update(['deleted_by' => $deleter->id]);

    $row = DB::table('websites')->where('id', $website->id)->first();
    expect($row->deleted_at)->not->toBeNull();
});

it('clears deleted_by when a raw update nulls deleted_at (restore)', function () {
    $website = Website::factory()->create();
    $deleter = User::factory()->create();
    Auth::login($deleter);
    $website->delete();

    DB::table('websites')->where('id', $website->id)->update(['deleted_at' => null]);

    $row = DB::table('websites')->where('id', $website->id)->first();
    expect($row->deleted_by)->toBeNull();
});

it('rejects a second live membership for the same user on the same website', function () {
    $website = Website::factory()->create();
    $member = User::factory()->create();
    $actor = User::factory()->create();

    WebsiteUser::factory()->create([
        'website_id' => $website->id,
        'user_id' => $member->id,
        'created_by' => $actor->id,
    ]);

    expect(fn () => WebsiteUser::factory()->create([
        'website_id' => $website->id,
        'user_id' => $member->id,
        'role' => WebsiteRole::Viewer,
        'created_by' => $actor->id,
    ]))->toThrow(QueryException::class);
});

it('allows re-inviting a user after their previous membership was removed', function () {
    $website = Website::factory()->create();
    $member = User::factory()->create();
    $actor = User::factory()->create();
    Auth::login($actor);

    $first = WebsiteUser::factory()->create([
        'website_id' => $website->id,
        'user_id' => $member->id,
        'created_by' => $actor->id,
    ]);
    $first->delete();

    $second = WebsiteUser::factory()->create([
        'website_id' => $website->id,
        'user_id' => $member->id,
        'role' => WebsiteRole::Admin,
        'created_by' => $actor->id,
    ]);

    expect($second->id)->not->toBe($first->id);
    expect(WebsiteUser::where('website_id', $website->id)->where('user_id', $member->id)->count())->toBe(1);
});
