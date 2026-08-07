<?php

namespace App\Policies;

use App\Enums\WebsiteRole;
use App\Models\Page;
use App\Models\User;
use App\Models\Website;
use App\Policies\Concerns\AuthorizesWebsiteAccess;

class PagePolicy
{
    use AuthorizesWebsiteAccess;

    /**
     * Determine whether the user can view the website's pages.
     */
    public function viewAny(User $user, Website $website): bool
    {
        return $user->roleOn($website) !== null;
    }

    /**
     * Determine whether the user can view the page.
     */
    public function view(User $user, Page $page): bool
    {
        return $user->roleOn($page->website) !== null;
    }

    /**
     * Determine whether the user can create a page on the website.
     */
    public function create(User $user, Website $website): bool
    {
        return $this->authorize($user, $website, WebsiteRole::Admin, WebsiteRole::Editor);
    }

    /**
     * Determine whether the user can update the page (slug, status).
     */
    public function update(User $user, Page $page): bool
    {
        return $this->authorize($user, $page->website, WebsiteRole::Admin, WebsiteRole::Editor);
    }

    /**
     * Determine whether the user can delete the page.
     */
    public function delete(User $user, Page $page): bool
    {
        return $this->authorize($user, $page->website, WebsiteRole::Admin);
    }

    /**
     * Determine whether the user can restore the page.
     */
    public function restore(User $user, Page $page): bool
    {
        return $user->roleOn($page->website) === WebsiteRole::Owner;
    }

    /**
     * Determine whether the user can permanently delete the page.
     */
    public function forceDelete(User $user, Page $page): bool
    {
        return $user->roleOn($page->website) === WebsiteRole::Owner;
    }
}
