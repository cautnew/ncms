<?php

namespace App\DTOs\WebsiteUser;

use App\Enums\WebsiteRole;
use App\Http\Requests\WebsiteUser\UpdateWebsiteUserRoleRequest;

final readonly class UpdateWebsiteUserRoleData
{
    public function __construct(
        public WebsiteRole $role,
    ) {}

    public static function fromRequest(UpdateWebsiteUserRoleRequest $request): self
    {
        return new self(
            role: WebsiteRole::from($request->string('role')->toString()),
        );
    }
}
