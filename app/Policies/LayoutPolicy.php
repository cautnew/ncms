<?php

namespace App\Policies;

use App\Enums\WebsiteRole;
use App\Models\Layout;
use App\Models\User;
use App\Models\Website;
use App\Policies\Concerns\AuthorizesWebsiteAccess;

class LayoutPolicy
{
    use AuthorizesWebsiteAccess;

    /**
     * Determine whether the user can view the website's layouts.
     */
    public function viewAny(User $user, Website $website): bool
    {
        return $user->roleOn($website) !== null;
    }

    /**
     * Determine whether the user can view the layout.
     */
    public function view(User $user, Layout $layout): bool
    {
        return $user->roleOn($layout->website) !== null;
    }

    /**
     * Determine whether the user can create a layout for the website.
     */
    public function create(User $user, Website $website): bool
    {
        return $this->authorize($user, $website, WebsiteRole::Admin, WebsiteRole::Editor);
    }

    /**
     * Determine whether the user can update the layout.
     */
    public function update(User $user, Layout $layout): bool
    {
        return $this->authorize($user, $layout->website, WebsiteRole::Admin, WebsiteRole::Editor);
    }

    /**
     * Determine whether the user can delete the layout.
     */
    public function delete(User $user, Layout $layout): bool
    {
        return $this->authorize($user, $layout->website, WebsiteRole::Admin);
    }

    /**
     * Determine whether the user can restore the layout.
     */
    public function restore(User $user, Layout $layout): bool
    {
        return $user->roleOn($layout->website) === WebsiteRole::Owner;
    }

    /**
     * Determine whether the user can permanently delete the layout.
     */
    public function forceDelete(User $user, Layout $layout): bool
    {
        return $user->roleOn($layout->website) === WebsiteRole::Owner;
    }
}
