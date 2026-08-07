<?php

namespace App\Services\Auth;

use App\DTOs\Auth\LoginData;
use App\Events\UserLoggedIn;
use App\Events\UserLoggedOut;
use App\Models\User;
use App\Support\RequestAuditContext;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class AuthenticationService
{
    public function __construct(
        private readonly RequestAuditContext $auditContext,
    ) {}

    /**
     * @return array{user: User, token: string}
     */
    public function login(LoginData $data): array
    {
        $user = User::where('email', $data->email)->first();

        if (! $user || ! Hash::check($data->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        event(new UserLoggedIn($user->id, $this->auditContext->ip(), $this->auditContext->userAgent()));

        return [
            'user' => $user,
            'token' => $user->createToken($data->deviceName)->plainTextToken,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();

        event(new UserLoggedOut($user->id, $this->auditContext->ip(), $this->auditContext->userAgent()));
    }
}
