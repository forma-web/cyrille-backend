<?php

namespace App\Services\V1;

use App\DTO\V1\LoginUserDTO;
use App\DTO\V1\TokenDTO;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class AuthenticationService
{
    public function forceLogin(User $user): TokenDTO
    {
        $token = Auth::login($user);

        return $this->tokenDTOFactory($token);
    }

    public function login(LoginUserDTO $credentials): TokenDTO
    {
        $token = Auth::attempt($credentials->toArray());

        abort_if(
            ! $token,
            Response::HTTP_UNAUTHORIZED,
            __('auth.failed'),
        );

        return $this->tokenDTOFactory($token);
    }

    public function refresh(): TokenDTO
    {
        $token = Auth::refresh();

        return $this->tokenDTOFactory($token);
    }

    public function current(): Authenticatable|null
    {
        return Auth::user();
    }

    public function logout(): void
    {
        Auth::logout();
    }

    private function tokenDTOFactory(string $token): TokenDTO
    {
        return new TokenDTO(
            token: $token,
            token_type: 'bearer',
            ttl: config('jwt.ttl') * 60,
        );
    }
}
