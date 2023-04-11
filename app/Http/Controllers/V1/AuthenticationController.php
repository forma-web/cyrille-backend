<?php

namespace App\Http\Controllers\V1;

use App\DTO\UserDTO;
use App\Http\Requests\V1\UserLoginRequest;
use App\Http\Requests\V1\UserRegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\V1\AuthService;
use App\Services\V1\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class AuthenticationController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly AuthService $authService,
    ) {
    }

    public function register(UserRegisterRequest $request): UserResource
    {
        $userDTO = UserDTO::fromRequest($request);

        $user = $this->userService->store($userDTO);

        $token = $this->authService->login($user);

        return (new UserResource($user))->additional([
            'meta' => $token->toArray(),
        ]);
    }

    public function login(UserLoginRequest $request): UserResource
    {
        $credentials = $request->validated();

        /** @var string|null $token */
        $token = auth()->attempt($credentials->all());

        abort_if(
            ! $token,
            SymfonyResponse::HTTP_UNAUTHORIZED,
            __('auth.failed'),
        );

        return $this->current()->additional([
            'meta' => $this->withToken($token),
        ]);
    }

    public function logout(): Response
    {
        auth()->logout();

        return response()->noContent();
    }

    public function refresh(): JsonResponse
    {
        /** @var string $token */
        $token = auth()->refresh(true, true); // @phpstan-ignore-line

        return response()->json([
            'meta' => $this->withToken($token),
        ]);
    }

    public function current(): UserResource
    {
        return new UserResource(auth()->user());
    }

    private function withToken(string $token): array
    {
        return [
            'token' => $token,
            'token_type' => 'bearer',
            'ttl' => auth()->factory()->getTTL() * 60, // @phpstan-ignore-line
        ];
    }

    private function hashCredentialsPassword(Collection $credentials): Collection
    {
        return $credentials->replaceByKey('password', fn ($p) => Hash::make($p));
    }
}
