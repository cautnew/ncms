<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\DTOs\Auth\UpdateProfileData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Services\Auth\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(
        private readonly ProfileService $profiles,
    ) {}

    /**
     * Show the authenticated user's profile.
     */
    public function show(Request $request): JsonResponse
    {
        return ApiResponse::success(new UserResource($request->user()));
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $this->profiles->update($request->user(), UpdateProfileData::fromRequest($request));

        return ApiResponse::success(new UserResource($user), 'Profile updated.');
    }
}
