<?php

namespace App\Policies;

use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteUser;
use App\Policies\Concerns\AuthorizesWebsiteAccess;

class WebsiteUserPolicy
{
    use AuthorizesWebsiteAccess;

    /**
     * Determine whether the user can view the member list of the website.
     */
    public function viewAny(User $user, Website $website): bool
    {
        return $this->authorize($user, $website, WebsiteRole::Admin);
    }

    /**
     * Determine whether the user can view a single membership.
     */
    public function view(User $user, WebsiteUser $websiteUser): bool
    {
        return $this->authorize($user, $websiteUser->website, WebsiteRole::Admin);
    }

    /**
     * Determine whether the user can invite a new member with the given role.
     * Admins may invite, but never with an owner/admin role.
     */
    public function create(User $user, Website $website, ?WebsiteRole $role = null): bool
    {
        $actorRole = $user->roleOn($website);

        if ($actorRole === WebsiteRole::Owner) {
            return true;
        }

        if ($actorRole !== WebsiteRole::Admin) {
            return false;
        }

        return $role === null || in_array($role, WebsiteRole::assignableByAdmin(), true);
    }

    /**
     * Determine whether the user can change a member's role.
     * Admins can never touch an owner/admin membership, nor promote anyone to owner/admin.
     */
    public function update(User $user, WebsiteUser $websiteUser, ?WebsiteRole $newRole = null): bool
    {
        $actorRole = $user->roleOn($websiteUser->website);

        if ($actorRole === WebsiteRole::Owner) {
            return true;
        }

        if ($actorRole !== WebsiteRole::Admin) {
            return false;
        }

        if (in_array($websiteUser->role, [WebsiteRole::Owner, WebsiteRole::Admin], true)) {
            return false;
        }

        return $newRole === null || in_array($newRole, WebsiteRole::assignableByAdmin(), true);
    }

    /**
     * Determine whether the user can remove a member.
     * Admins may remove anyone except an owner.
     */
    public function delete(User $user, WebsiteUser $websiteUser): bool
    {
        $actorRole = $user->roleOn($websiteUser->website);

        if ($actorRole === WebsiteRole::Owner) {
            return true;
        }

        if ($actorRole !== WebsiteRole::Admin) {
            return false;
        }

        return $websiteUser->role !== WebsiteRole::Owner;
    }
}
