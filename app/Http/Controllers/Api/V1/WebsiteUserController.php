<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\WebsiteUser\InviteWebsiteUserData;
use App\DTOs\WebsiteUser\UpdateWebsiteUserRoleData;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebsiteUser\InviteWebsiteUserRequest;
use App\Http\Requests\WebsiteUser\UpdateWebsiteUserRoleRequest;
use App\Http\Resources\WebsiteUserResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use App\Models\Website;
use App\Models\WebsiteUser;
use App\Services\WebsiteUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Attributes\Controllers\Authorize;

class WebsiteUserController extends Controller
{
    public function __construct(
        private readonly WebsiteUserService $websiteUsers,
    ) {}

    /**
     * List the website's members.
     *
     * WebsiteUser::class must be given explicitly: passing only the 'website' route
     * param would let Laravel infer the policy from Website's class (WebsitePolicy,
     * whose viewAny() is unconditionally true for any member) instead of the
     * WebsiteUserPolicy::viewAny() we actually want here (owner/admin only).
     */
    #[Authorize('viewAny', [WebsiteUser::class, 'website'])]
    public function index(Website $website): JsonResponse
    {
        $members = $this->websiteUsers->listForWebsite($website);

        return ApiResponse::success(WebsiteUserResource::collection($members));
    }

    /**
     * Invite a new member by email. Authorization is checked in
     * InviteWebsiteUserRequest::authorize(), since it depends on the requested role.
     */
    public function store(InviteWebsiteUserRequest $request, Website $website): JsonResponse
    {
        $membership = $this->websiteUsers->invite(
            $website,
            $request->user(),
            InviteWebsiteUserData::fromRequest($request),
        );

        return ApiResponse::success(new WebsiteUserResource($membership), 'User invited.', 201);
    }

    /**
     * Change a member's role. Authorization is checked in
     * UpdateWebsiteUserRoleRequest::authorize(), since it depends on the requested role.
     */
    public function update(UpdateWebsiteUserRoleRequest $request, Website $website, User $user): JsonResponse
    {
        $membership = $this->websiteUsers->changeRole(
            $website,
            $user,
            UpdateWebsiteUserRoleData::fromRequest($request),
        );

        return ApiResponse::success(new WebsiteUserResource($membership), 'Role updated.');
    }

    /**
     * Remove a member from the website.
     */
    public function destroy(Website $website, User $user): JsonResponse
    {
        $membership = $this->websiteUsers->findMembership($website, $user);

        $this->authorize('delete', $membership);

        $this->websiteUsers->remove($membership);

        return ApiResponse::success(message: 'User removed from website.');
    }
}
