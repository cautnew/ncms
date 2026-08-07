<?php

namespace App\DTOs\Auth;

use App\Http\Requests\Auth\ResetPasswordRequest;

final readonly class ResetPasswordData
{
    public function __construct(
        public string $token,
        public string $email,
        public string $password,
    ) {}

    public static function fromRequest(ResetPasswordRequest $request): self
    {
        return new self(
            token: $request->string('token')->toString(),
            email: $request->string('email')->toString(),
            password: $request->string('password')->toString(),
        );
    }
}
