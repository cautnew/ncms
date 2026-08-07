<?php

namespace App\DTOs\WebsiteUser;

use App\Enums\WebsiteRole;
use App\Http\Requests\WebsiteUser\InviteWebsiteUserRequest;

final readonly class InviteWebsiteUserData
{
    public function __construct(
        public string $email,
        public ?string $name,
        public WebsiteRole $role,
    ) {}

    public static function fromRequest(InviteWebsiteUserRequest $request): self
    {
        return new self(
            email: $request->string('email')->toString(),
            name: $request->filled('name') ? $request->string('name')->toString() : null,
            role: WebsiteRole::from($request->string('role')->toString()),
        );
    }
}
