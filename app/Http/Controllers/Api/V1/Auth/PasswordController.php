<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\DTOs\Auth\ChangePasswordData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Auth\ProfileService;
use Illuminate\Http\JsonResponse;

class PasswordController extends Controller
{
    public function __construct(
        private readonly ProfileService $profiles,
    ) {}

    /**
     * Change the authenticated user's password.
     */
    public function update(ChangePasswordRequest $request): JsonResponse
    {
        $this->profiles->changePassword($request->user(), ChangePasswordData::fromRequest($request));

        return ApiResponse::success(message: 'Password updated.');
    }
}
