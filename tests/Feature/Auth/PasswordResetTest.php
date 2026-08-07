<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

// --- forgot-password ---------------------------------------------------------------------------

it('sends a reset link notification for an existing email', function () {
    Notification::fake();
    $user = User::factory()->create();

    $this->postJson(route('api.v1.auth.password.email'), ['email' => $user->email])
        ->assertSuccessful()
        ->assertJson([
            'success' => true,
            'message' => 'If an account exists for that email, a password reset link has been sent.',
        ]);

    Notification::assertSentTo($user, ResetPassword::class);
});

it('returns the same success response for an unknown email without sending anything', function () {
    Notification::fake();

    $this->postJson(route('api.v1.auth.password.email'), ['email' => 'nobody@example.com'])
        ->assertSuccessful()
        ->assertJson(['success' => true]);

    Notification::assertNothingSent();
});

it('validates the email field on forgot-password', function () {
    $this->postJson(route('api.v1.auth.password.email'), ['email' => 'not-an-email'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('throttles repeated reset-link requests for the same email', function () {
    Notification::fake();
    $user = User::factory()->create();

    $this->postJson(route('api.v1.auth.password.email'), ['email' => $user->email])->assertSuccessful();

    $this->postJson(route('api.v1.auth.password.email'), ['email' => $user->email])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

// --- reset-password ------------------------------------------------------------------------------

it('resets the password with a valid token and revokes existing tokens', function () {
    $user = User::factory()->create();
    $staleToken = $user->createToken('old-device')->plainTextToken;

    $token = Password::createToken($user);

    $this->postJson(route('api.v1.auth.password.reset'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'new-strong-password',
        'password_confirmation' => 'new-strong-password',
    ])
        ->assertSuccessful()
        ->assertJson(['success' => true, 'message' => 'Your password has been reset.']);

    expect(Hash::check('new-strong-password', $user->fresh()->password))->toBeTrue();
    expect($user->tokens()->count())->toBe(0);

    $this->withHeaders(['Authorization' => "Bearer {$staleToken}"])
        ->getJson(route('api.v1.auth.profile.show'))
        ->assertUnauthorized();
});

it('rejects reset-password with an invalid token', function () {
    $user = User::factory()->create();

    $this->postJson(route('api.v1.auth.password.reset'), [
        'token' => 'not-a-real-token',
        'email' => $user->email,
        'password' => 'new-strong-password',
        'password_confirmation' => 'new-strong-password',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('rejects reset-password when the confirmation does not match', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $this->postJson(route('api.v1.auth.password.reset'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'new-strong-password',
        'password_confirmation' => 'something-else',
    ])->assertUnprocessable()->assertJsonValidationErrors(['password']);
});

it('rejects reset-password with a password shorter than the minimum', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $this->postJson(route('api.v1.auth.password.reset'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'short',
        'password_confirmation' => 'short',
    ])->assertUnprocessable()->assertJsonValidationErrors(['password']);
});

it('validates required fields on reset-password', function () {
    $this->postJson(route('api.v1.auth.password.reset'), [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['token', 'email', 'password']);
});
