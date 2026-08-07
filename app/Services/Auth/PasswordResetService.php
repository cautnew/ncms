<?php

namespace App\Services\Auth;

use App\DTOs\Auth\ForgotPasswordData;
use App\DTOs\Auth\ResetPasswordData;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

final class PasswordResetService
{
    /**
     * Request a password reset link. Deliberately does not distinguish between
     * "email sent" and "no account with that email" in its outcome, to avoid
     * leaking which addresses are registered (user enumeration).
     */
    public function sendResetLink(ForgotPasswordData $data): void
    {
        $status = Password::sendResetLink(['email' => $data->email]);

        if ($status === Password::RESET_THROTTLED) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }
    }

    /**
     * Consume a reset token and set the new password.
     * Revokes every existing API token for the user, forcing re-login everywhere.
     */
    public function reset(ResetPasswordData $data): void
    {
        $status = Password::reset(
            [
                'email' => $data->email,
                'token' => $data->token,
                'password' => $data->password,
            ],
            function (User $user, string $password): void {
                $user->forceFill(['password' => $password])->save();
                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }
    }
}
