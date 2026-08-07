<?php

namespace App\Repositories\Contracts;

use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteUser;
use Illuminate\Database\Eloquent\Collection;

interface WebsiteUserRepositoryInterface
{
    /**
     * @return Collection<int, WebsiteUser>
     */
    public function allForWebsite(Website $website): Collection;

    /**
     * Find the membership linking the given user to the given website.
     * Throws a ModelNotFoundException if none exists.
     */
    public function findMembership(Website $website, User $user): WebsiteUser;

    public function create(Website $website, User $user, User $inviter, WebsiteRole $role): WebsiteUser;

    public function updateRole(WebsiteUser $membership, WebsiteRole $role): WebsiteUser;

    public function delete(WebsiteUser $membership): void;
}
