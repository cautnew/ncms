<?php

namespace App\DTOs\Auth;

use App\Http\Requests\Auth\UpdateProfileRequest;

final readonly class UpdateProfileData
{
    public function __construct(
        public string $name,
        public string $email,
    ) {}

    public static function fromRequest(UpdateProfileRequest $request): self
    {
        return new self(
            name: $request->string('name')->toString(),
            email: $request->string('email')->toString(),
        );
    }
}
