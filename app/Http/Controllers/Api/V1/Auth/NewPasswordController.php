<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\DTOs\Auth\ResetPasswordData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Auth\PasswordResetService;
use Illuminate\Http\JsonResponse;

class NewPasswordController extends Controller
{
    public function __construct(
        private readonly PasswordResetService $passwords,
    ) {}

    /**
     * Consume a password reset token and set a new password.
     */
    public function store(ResetPasswordRequest $request): JsonResponse
    {
        $this->passwords->reset(ResetPasswordData::fromRequest($request));

        return ApiResponse::success(message: 'Your password has been reset.');
    }
}
