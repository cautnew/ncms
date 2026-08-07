<?php

namespace App\Repositories\Eloquent;

use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteUser;
use App\Repositories\Contracts\WebsiteUserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class WebsiteUserRepository implements WebsiteUserRepositoryInterface
{
    /**
     * @return Collection<int, WebsiteUser>
     */
    public function allForWebsite(Website $website): Collection
    {
        return $website->websiteUsers()->with('user')->get();
    }

    public function findMembership(Website $website, User $user): WebsiteUser
    {
        return $website->websiteUsers()
            ->with('user')
            ->where('user_id', $user->id)
            ->firstOrFail();
    }

    public function create(Website $website, User $user, User $inviter, WebsiteRole $role): WebsiteUser
    {
        return $website->websiteUsers()->create([
            'user_id' => $user->id,
            'role' => $role,
            'invited_by' => $inviter->id,
            'invited_at' => now(),
        ])->fresh(['user']);
    }

    public function updateRole(WebsiteUser $membership, WebsiteRole $role): WebsiteUser
    {
        $membership->update(['role' => $role]);

        return $membership->fresh(['user']);
    }

    public function delete(WebsiteUser $membership): void
    {
        $membership->delete();
    }
}
