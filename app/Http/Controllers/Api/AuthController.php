<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailVerificationRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Traits\ResponseTrait;

class AuthController extends Controller
{
    use ResponseTrait;
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->authService->registerUser($request);
    }

    public function verifyEmail(EmailVerificationRequest $request): JsonResponse
    {
        return $this->authService->VerifyEmail($request);
    }

    public function login(Request $request): JsonResponse
    {
        return $this->authService->login($request);
    }
}
