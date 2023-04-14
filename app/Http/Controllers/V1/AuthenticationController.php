<?php

namespace App\Http\Controllers\V1;

use App\DTO\V1\RegisterUserDTO;
use App\Http\Requests\V1\UserLoginRequest;
use App\Http\Requests\V1\UserRegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\V1\AuthenticationService;
use App\Services\V1\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class AuthenticationController extends Controller
{
    public function __construct(
        private readonly UserService           $userService,
        private readonly AuthenticationService $authService,
    ) {
    }

    public function register(UserRegisterRequest $request): UserResource
    {
        $user = $this->userService->store(
            RegisterUserDTO::fromRequest($request),
        );

        $token = $this->authService->forceLogin($user);

        return UserResource::make($user)->additional([
            'meta' => $token->toArray(),
        ]);
    }

    public function login(UserLoginRequest $request): UserResource
    {
//        $credentials = $request->validated();
//
//        /** @var string|null $token */
//        $token = auth()->attempt($credentials->all());
//
//        abort_if(
//            ! $token,
//            SymfonyResponse::HTTP_UNAUTHORIZED,
//            __('auth.failed'),
//        );
//
//        return $this->current()->additional([
//            'meta' => $this->withToken($token),
//        ]);
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
}
