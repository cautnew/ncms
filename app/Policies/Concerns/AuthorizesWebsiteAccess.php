<?php

namespace App\Policies\Concerns;

use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;

trait AuthorizesWebsiteAccess
{
    /**
     * Whether the user holds one of the given roles on the website.
     * Owners always pass, regardless of the roles listed.
     */
    protected function authorize(User $user, Website $website, WebsiteRole ...$roles): bool
    {
        $role = $user->roleOn($website);

        if ($role === null) {
            return false;
        }

        if ($role === WebsiteRole::Owner) {
            return true;
        }

        return in_array($role, $roles, true);
    }
}
