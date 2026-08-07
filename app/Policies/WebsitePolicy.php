<?php

namespace App\Policies;

use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;
use App\Policies\Concerns\AuthorizesWebsiteAccess;

class WebsitePolicy
{
    use AuthorizesWebsiteAccess;

    /**
     * Determine whether the user can view the list of websites they belong to.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the website.
     */
    public function view(User $user, Website $website): bool
    {
        return $user->roleOn($website) !== null;
    }

    /**
     * Determine whether the user can create a new website.
     * Any authenticated user may create one; they become its owner.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the website (domain, locale, status, ...).
     */
    public function update(User $user, Website $website): bool
    {
        return $this->authorize($user, $website, WebsiteRole::Admin);
    }

    /**
     * Determine whether the user can delete the website.
     */
    public function delete(User $user, Website $website): bool
    {
        return $user->roleOn($website) === WebsiteRole::Owner;
    }

    /**
     * Determine whether the user can restore the website.
     */
    public function restore(User $user, Website $website): bool
    {
        return $user->roleOn($website) === WebsiteRole::Owner;
    }

    /**
     * Determine whether the user can permanently delete the website.
     */
    public function forceDelete(User $user, Website $website): bool
    {
        return $user->roleOn($website) === WebsiteRole::Owner;
    }
}
