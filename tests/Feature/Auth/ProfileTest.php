<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// --- show ------------------------------------------------------------------------------------

it('shows the authenticated user profile', function () {
    $user = User::factory()->create(['name' => 'Ana Silva']);

    $this->actingAs($user)
        ->getJson(route('api.v1.auth.profile.show'))
        ->assertSuccessful()
        ->assertJsonPath('data.id', $user->id)
        ->assertJsonPath('data.name', 'Ana Silva')
        ->assertJsonPath('data.email', $user->email);
});

it('rejects showing the profile without authentication', function () {
    $this->getJson(route('api.v1.auth.profile.show'))->assertUnauthorized();
});

// --- update ----------------------------------------------------------------------------------

it('updates the profile name and email', function () {
    $user = User::factory()->create(['name' => 'Old Name', 'email' => 'old@example.com']);

    $response = $this->actingAs($user)->putJson(route('api.v1.auth.profile.update'), [
        'name' => 'New Name',
        'email' => 'new@example.com',
    ]);

    $response->assertSuccessful()
        ->assertJson(['success' => true, 'message' => 'Profile updated.'])
        ->assertJsonPath('data.name', 'New Name')
        ->assertJsonPath('data.email', 'new@example.com');

    $user->refresh();
    expect($user->name)->toBe('New Name');
    expect($user->email)->toBe('new@example.com');
});

it('resets email verification when the email address changes', function () {
    $user = User::factory()->create(['email' => 'old@example.com', 'email_verified_at' => now()]);

    $this->actingAs($user)->putJson(route('api.v1.auth.profile.update'), [
        'name' => $user->name,
        'email' => 'changed@example.com',
    ])->assertSuccessful();

    expect($user->fresh()->email_verified_at)->toBeNull();
});

it('keeps email verification intact when the email is unchanged', function () {
    $user = User::factory()->create(['email' => 'stays@example.com', 'email_verified_at' => now()]);

    $this->actingAs($user)->putJson(route('api.v1.auth.profile.update'), [
        'name' => 'Renamed Only',
        'email' => 'stays@example.com',
    ])->assertSuccessful();

    expect($user->fresh()->email_verified_at)->not->toBeNull();
});

it('rejects a profile update to an email already used by another user', function () {
    $user = User::factory()->create();
    $other = User::factory()->create(['email' => 'taken@example.com']);

    $this->actingAs($user)->putJson(route('api.v1.auth.profile.update'), [
        'name' => $user->name,
        'email' => 'taken@example.com',
    ])->assertUnprocessable()->assertJsonValidationErrors(['email']);
});

it('allows a profile update that keeps the same email as itself', function () {
    $user = User::factory()->create(['email' => 'me@example.com']);

    $this->actingAs($user)->putJson(route('api.v1.auth.profile.update'), [
        'name' => 'Still Me',
        'email' => 'me@example.com',
    ])->assertSuccessful();
});

it('validates required fields on profile update', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->putJson(route('api.v1.auth.profile.update'), [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'email']);
});

it('rejects updating the profile without authentication', function () {
    $this->putJson(route('api.v1.auth.profile.update'), ['name' => 'X', 'email' => 'x@example.com'])
        ->assertUnauthorized();
});

// --- change password ---------------------------------------------------------------------------

it('changes the password given the correct current password', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->putJson(route('api.v1.auth.password.update'), [
        'current_password' => 'password',
        'password' => 'a-brand-new-password',
        'password_confirmation' => 'a-brand-new-password',
    ])
        ->assertSuccessful()
        ->assertJson(['success' => true, 'message' => 'Password updated.']);

    expect(Hash::check('a-brand-new-password', $user->fresh()->password))->toBeTrue();
});

it('revokes every other token when the password changes, keeping the current one', function () {
    $user = User::factory()->create();
    $currentToken = $user->createToken('current')->plainTextToken;
    $user->createToken('other-device');

    expect($user->tokens()->count())->toBe(2);

    $this->withHeaders(['Authorization' => "Bearer {$currentToken}"])
        ->putJson(route('api.v1.auth.password.update'), [
            'current_password' => 'password',
            'password' => 'a-brand-new-password',
            'password_confirmation' => 'a-brand-new-password',
        ])
        ->assertSuccessful();

    expect($user->tokens()->count())->toBe(1);

    $this->withHeaders(['Authorization' => "Bearer {$currentToken}"])
        ->getJson(route('api.v1.auth.profile.show'))
        ->assertSuccessful();
});

it('rejects a password change with the wrong current password', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->putJson(route('api.v1.auth.password.update'), [
        'current_password' => 'not-the-password',
        'password' => 'a-brand-new-password',
        'password_confirmation' => 'a-brand-new-password',
    ])->assertUnprocessable()->assertJsonValidationErrors(['current_password']);
});

it('rejects a password change when the confirmation does not match', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->putJson(route('api.v1.auth.password.update'), [
        'current_password' => 'password',
        'password' => 'a-brand-new-password',
        'password_confirmation' => 'something-else',
    ])->assertUnprocessable()->assertJsonValidationErrors(['password']);
});

it('rejects changing the password without authentication', function () {
    $this->putJson(route('api.v1.auth.password.update'), [
        'current_password' => 'password',
        'password' => 'a-brand-new-password',
        'password_confirmation' => 'a-brand-new-password',
    ])->assertUnauthorized();
});
