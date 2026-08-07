<?php

namespace App\Services;

use App\DTOs\Website\CreateWebsiteData;
use App\DTOs\Website\UpdateWebsiteData;
use App\Enums\WebsiteRole;
use App\Models\User;
use App\Models\Website;
use App\Repositories\Contracts\WebsiteRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class WebsiteService
{
    public function __construct(
        private readonly WebsiteRepositoryInterface $websites,
    ) {}

    /**
     * List the websites the given user has access to.
     */
    public function listForUser(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $this->websites->paginateForUser($user, $perPage);
    }

    /**
     * Create a new website. The creator is automatically granted the owner role.
     */
    public function create(User $creator, CreateWebsiteData $data): Website
    {
        $website = $this->websites->create($data->toArray());

        $website->websiteUsers()->create([
            'user_id' => $creator->id,
            'role' => WebsiteRole::Owner,
            'accepted_at' => now(),
        ]);

        return $website;
    }

    public function update(Website $website, UpdateWebsiteData $data): Website
    {
        return $this->websites->update($website, $data->toArray());
    }

    public function delete(Website $website): void
    {
        $this->websites->delete($website);
    }
}
