<?php

namespace App\Http\Controllers\V1;

use App\DTO\V1\LoginUserDTO;
use App\DTO\V1\RegisterUserDTO;
use App\Http\Requests\V1\LoginUserRequest;
use App\Http\Requests\V1\RegisterUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\V1\AuthenticationService;
use App\Services\V1\OtpService;
use App\Services\V1\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class AuthenticationController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly AuthenticationService $authService,
        private readonly OtpService $otpService,
    ) {
    }

    public function register(RegisterUserRequest $request): UserResource
    {
        $user = $this->userService->store(
            RegisterUserDTO::fromRequest($request),
        );

        $token = $this->authService->forceLogin($user);

        return UserResource::make($user)->additional([
            'meta' => $token->toArray(),
        ]);
    }

    public function login(LoginUserRequest $request): UserResource
    {
        $token = $this->authService->login(
            LoginUserDTO::fromRequest($request),
        );

        return UserResource::make($this->userService->current())->additional([
            'meta' => $token->toArray(),
        ]);
    }

    public function logout(): Response
    {
        $this->authService->logout();

        return response()->noContent();
    }

    public function refresh(): JsonResponse
    {
        return response()->json([
            'meta' => $this->authService->refresh(),
        ]);
    }

    public function resetPassword(Request $request): void
    {
        $request = $request->validate(
            ['email' => 'required|email'],
        );

        $this->authService->resetPassword($request['email']);
    }

    public function resetPasswordVerify(Request $request): void
    {
        $request = $request->validate([
            'code' => 'required|string|size:5',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $this->authService->resetPasswordVerify(
            $request['code'],
            $request['email'],
            $request['password'],
        );
    }
}
