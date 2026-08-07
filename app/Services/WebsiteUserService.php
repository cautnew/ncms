<?php

namespace App\Services;

use App\DTOs\WebsiteUser\InviteWebsiteUserData;
use App\DTOs\WebsiteUser\UpdateWebsiteUserRoleData;
use App\Events\WebsiteUserInvited;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteUser;
use App\Repositories\Contracts\WebsiteUserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

final class WebsiteUserService
{
    public function __construct(
        private readonly WebsiteUserRepositoryInterface $websiteUsers,
    ) {}

    /**
     * @return Collection<int, WebsiteUser>
     */
    public function listForWebsite(Website $website): Collection
    {
        return $this->websiteUsers->allForWebsite($website);
    }

    /**
     * Find the membership linking the given user to the given website.
     */
    public function findMembership(Website $website, User $user): WebsiteUser
    {
        return $this->websiteUsers->findMembership($website, $user);
    }

    /**
     * Invite a member by email. If no account exists for that email yet, one is
     * created (with an unusable random password — the invitee sets their own via
     * the "forgot password" flow) so the invitation always resolves to a real user.
     */
    public function invite(Website $website, User $inviter, InviteWebsiteUserData $data): WebsiteUser
    {
        $invitee = User::where('email', $data->email)->first();

        if ($invitee === null) {
            $invitee = User::create([
                'name' => $data->name ?? Str::before($data->email, '@'),
                'email' => $data->email,
                'password' => Str::random(40),
            ]);
        }

        $membership = $this->websiteUsers->create($website, $invitee, $inviter, $data->role);

        event(new WebsiteUserInvited($membership, $inviter));

        return $membership;
    }

    public function changeRole(Website $website, User $user, UpdateWebsiteUserRoleData $data): WebsiteUser
    {
        $membership = $this->websiteUsers->findMembership($website, $user);

        return $this->websiteUsers->updateRole($membership, $data->role);
    }

    public function remove(WebsiteUser $membership): void
    {
        $this->websiteUsers->delete($membership);
    }
}
