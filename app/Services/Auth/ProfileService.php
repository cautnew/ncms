<?php

namespace App\Services\Auth;

use App\DTOs\Auth\ChangePasswordData;
use App\DTOs\Auth\UpdateProfileData;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

final class ProfileService
{
    /**
     * Update the user's profile. Resets email verification when the address changes.
     */
    public function update(User $user, UpdateProfileData $data): User
    {
        $emailChanged = $data->email !== $user->email;

        $user->fill([
            'name' => $data->name,
            'email' => $data->email,
        ]);

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $user;
    }

    /**
     * Change the user's password and revoke every other active token,
     * keeping only the one used to make this request alive.
     *
     * currentAccessToken() only returns a real, database-backed
     * PersonalAccessToken when the request authenticated via a bearer token;
     * for other guards (e.g. a stateful/session-based request) it can be a
     * Sanctum TransientToken instead, which has no id — in that case there is
     * no specific token to spare, so every token the user holds is revoked.
     */
    public function changePassword(User $user, ChangePasswordData $data): void
    {
        $user->forceFill(['password' => $data->password])->save();

        $currentToken = $user->currentAccessToken();
        $currentTokenId = $currentToken instanceof PersonalAccessToken ? $currentToken->id : null;

        $user->tokens()
            ->when($currentTokenId !== null, fn ($query) => $query->where('id', '!=', $currentTokenId))
            ->delete();
    }
}
