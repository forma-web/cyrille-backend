<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\V1\UserLoginRequest;
use App\Http\Requests\V1\UserRegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    public function register(UserRegisterRequest $request): UserResource
    {
        $credentials = $request->validated();

        $user = User::create($this->hashCredentialsPassword($credentials)->all());

        /** @var string $token */
        $token = auth()->login($user); // @phpstan-ignore-line

        return $this->current()->additional([
            'meta' => $this->withToken($token),
        ]);
    }

    public function login(UserLoginRequest $request): UserResource
    {
        $credentials = $request->validated();

        /** @var string|null $token */
        $token = auth()->attempt($credentials->all());

        abort_if(
            ! $token,
            Response::HTTP_UNAUTHORIZED,
            __('auth.failed'),
        );

        return $this->current()->additional([
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
