<?php

namespace App\Http\Controllers\V1;

use App\DTO\V1\LoginUserDTO;
use App\DTO\V1\RegisterUserDTO;
use App\Http\Requests\V1\CheckRequest;
use App\Http\Requests\V1\LoginUserRequest;
use App\Http\Requests\V1\PasswordCheckRequest;
use App\Http\Requests\V1\PasswordResetRequest;
use App\Http\Requests\V1\PasswordVerifyRequest;
use App\Http\Requests\V1\RegisterUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\V1\AuthenticationService;
use App\Services\V1\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class AuthenticationController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly AuthenticationService $authService,
    ) {
    }

    public function register(RegisterUserRequest $request): UserResource
    {
        $user = $this->userService->store(
            RegisterUserDTO::fromRequest($request),
        );

        abort_if(
            ! $user,
            SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY,
            __('auth.exists'),
        );

        $token = $this->authService->forceLogin($user);

        return UserResource::make($user)->additional([
            'meta' => $token->toArray(),
        ]);
    }

    public function check(CheckRequest $request): Response
    {
        $code = $request->validated('code');

        abort_if(
            ! $this->userService->check($code),
            SymfonyResponse::HTTP_UNAUTHORIZED,
            __('auth.otp.invalid')
        );

        return response()->noContent();
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

    public function passwordVerify(PasswordVerifyRequest $request): Response
    {
        $email = $request->validated('email');

        $this->authService->passwordVerify($email);

        return response()->noContent();
    }

    public function passwordCheck(PasswordCheckRequest $request): Response
    {
        $email = $request->validated('email');
        $code = $request->validated('code');

        abort_if(
            ! $this->authService->passwordCheck($email, $code),
            SymfonyResponse::HTTP_UNAUTHORIZED,
            __('auth.otp.invalid'),
        );

        return response()->noContent();
    }

    public function passwordReset(PasswordResetRequest $request): Response
    {
        $email = $request->validated('email');
        $password = $request->validated('password');

        abort_if(
            ! $this->authService->passwordReset($email, $password),
            SymfonyResponse::HTTP_UNAUTHORIZED,
            __('auth.otp.invalid'),
        );

        return response()->noContent();
    }
}
