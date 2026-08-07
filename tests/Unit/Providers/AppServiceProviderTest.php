<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;

it('builds the password reset link pointing at the frontend, not a Laravel route', function () {
    config(['app.frontend_url' => 'https://app.example.com/']);
    $user = User::factory()->create(['email' => 'reset-me@example.com']);

    $url = call_user_func(ResetPassword::$createUrlCallback, $user, 'sometoken123');

    expect($url)->toBe('https://app.example.com/reset-password?token=sometoken123&email=reset-me%40example.com');
});
