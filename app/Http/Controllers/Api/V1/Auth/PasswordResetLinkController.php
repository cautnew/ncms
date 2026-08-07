<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\DTOs\Auth\ForgotPasswordData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Auth\PasswordResetService;
use Illuminate\Http\JsonResponse;

class PasswordResetLinkController extends Controller
{
    public function __construct(
        private readonly PasswordResetService $passwords,
    ) {}

    /**
     * Send a password reset link to the given email, if an account exists for it.
     */
    public function store(ForgotPasswordRequest $request): JsonResponse
    {
        $this->passwords->sendResetLink(ForgotPasswordData::fromRequest($request));

        return ApiResponse::success(
            message: 'If an account exists for that email, a password reset link has been sent.',
        );
    }
}
