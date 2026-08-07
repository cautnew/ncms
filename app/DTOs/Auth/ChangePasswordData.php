<?php

namespace App\DTOs\Auth;

use App\Http\Requests\Auth\ChangePasswordRequest;

final readonly class ChangePasswordData
{
    public function __construct(
        public string $password,
    ) {}

    public static function fromRequest(ChangePasswordRequest $request): self
    {
        return new self(
            password: $request->string('password')->toString(),
        );
    }
}
