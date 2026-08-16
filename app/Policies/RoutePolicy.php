<?php

namespace App\Policies;

use App\Enums\WebsiteRole;
use App\Models\Route;
use App\Models\User;
use App\Models\Website;
use App\Policies\Concerns\AuthorizesWebsiteAccess;

class RoutePolicy
{
    use AuthorizesWebsiteAccess;

    /**
     * Determine whether the user can view the website's routes.
     */
    public function viewAny(User $user, Website $website): bool
    {
        return $user->roleOn($website) !== null;
    }

    /**
     * Determine whether the user can view the route.
     */
    public function view(User $user, Route $route): bool
    {
        return $user->roleOn($route->website) !== null;
    }

    /**
     * Determine whether the user can create a route for the website.
     */
    public function create(User $user, Website $website): bool
    {
        return $this->authorize($user, $website, WebsiteRole::Admin, WebsiteRole::Editor);
    }

    /**
     * Determine whether the user can update the route.
     */
    public function update(User $user, Route $route): bool
    {
        return $this->authorize($user, $route->website, WebsiteRole::Admin, WebsiteRole::Editor);
    }

    /**
     * Determine whether the user can delete the route.
     */
    public function delete(User $user, Route $route): bool
    {
        return $this->authorize($user, $route->website, WebsiteRole::Admin);
    }

    /**
     * Determine whether the user can restore the route.
     */
    public function restore(User $user, Route $route): bool
    {
        return $user->roleOn($route->website) === WebsiteRole::Owner;
    }

    /**
     * Determine whether the user can permanently delete the route.
     */
    public function forceDelete(User $user, Route $route): bool
    {
        return $user->roleOn($route->website) === WebsiteRole::Owner;
    }
}
