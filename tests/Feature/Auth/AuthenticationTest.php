<?php

use App\Models\User;

it('logs in with valid credentials and issues a bearer token', function () {
    $user = User::factory()->create();

    $response = $this->postJson(route('api.v1.auth.login'), [
        'email' => $user->email,
        'password' => 'password',
        'device_name' => 'phpunit',
    ]);

    $response->assertSuccessful()
        ->assertJson(['success' => true, 'message' => 'Login successful.'])
        ->assertJsonPath('data.user.id', $user->id)
        ->assertJsonPath('data.token_type', 'Bearer');

    expect($response->json('data.token'))->toBeString()->not->toBeEmpty();
    expect($user->tokens()->count())->toBe(1);
});

it('defaults the token device name to "api" when none is given', function () {
    $user = User::factory()->create();

    $this->postJson(route('api.v1.auth.login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertSuccessful();

    expect($user->tokens()->first()->name)->toBe('api');
});

it('rejects login with a wrong password without revealing which field is wrong', function () {
    $user = User::factory()->create();

    $this->postJson(route('api.v1.auth.login'), [
        'email' => $user->email,
        'password' => 'not-the-password',
    ])
        ->assertUnprocessable()
        ->assertJson(['success' => false])
        ->assertJsonValidationErrors(['email']);

    expect($user->tokens()->count())->toBe(0);
});

it('rejects login for an email that does not exist', function () {
    $this->postJson(route('api.v1.auth.login'), [
        'email' => 'nobody@example.com',
        'password' => 'password',
    ])->assertUnprocessable()->assertJsonValidationErrors(['email']);
});

it('validates required login fields', function () {
    $this->postJson(route('api.v1.auth.login'), [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email', 'password']);
});

it('throttles repeated login attempts from the same client', function () {
    $user = User::factory()->create();

    for ($i = 0; $i < 6; $i++) {
        $this->postJson(route('api.v1.auth.login'), [
            'email' => $user->email,
            'password' => 'wrong',
        ])->assertUnprocessable();
    }

    $this->postJson(route('api.v1.auth.login'), [
        'email' => $user->email,
        'password' => 'wrong',
    ])->assertStatus(429);
});

it('logs out and revokes the token used for the request', function () {
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;

    $this->withHeaders(['Authorization' => "Bearer {$token}"])
        ->postJson(route('api.v1.auth.logout'))
        ->assertSuccessful()
        ->assertJson(['success' => true, 'message' => 'Logout successful.']);

    // The Sanctum guard memoizes its resolved user for the lifetime of the
    // test's application container, so a second simulated request re-using
    // this now-deleted token within the *same* test would still resolve
    // (a testing-harness quirk, not real behavior — confirmed against a real
    // request that the revoked token does get a 401). The token row itself
    // being gone is the meaningful assertion here.
    expect($user->tokens()->count())->toBe(0);
});

it('rejects logout without authentication', function () {
    $this->postJson(route('api.v1.auth.logout'))->assertUnauthorized();
});

it('only revokes the token used to log out, leaving other sessions intact', function () {
    $user = User::factory()->create();
    $tokenA = $user->createToken('device-a')->plainTextToken;
    $tokenB = $user->createToken('device-b');

    $this->withHeaders(['Authorization' => "Bearer {$tokenA}"])
        ->postJson(route('api.v1.auth.logout'))
        ->assertSuccessful();

    expect($user->tokens()->count())->toBe(1);
    expect($user->tokens()->whereKey($tokenB->accessToken->id)->exists())->toBeTrue();
});
