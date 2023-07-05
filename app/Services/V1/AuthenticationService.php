<?php

namespace App\Services\V1;

use App\DTO\V1\LoginUserDTO;
use App\DTO\V1\TokenDTO;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class AuthenticationService
{
    public function forceLogin(User $user): TokenDTO
    {
        $token = Auth::login($user);

        return TokenDTO::bearerFactory($token);
    }

    public function login(LoginUserDTO $credentials): TokenDTO
    {
        $token = Auth::attempt($credentials->toArray());

        abort_if(
            ! $token,
            Response::HTTP_UNAUTHORIZED,
            __('auth.failed'),
        );

        return TokenDTO::bearerFactory($token);
    }

    public function refresh(): TokenDTO
    {
        $token = Auth::refresh();

        return TokenDTO::bearerFactory($token);
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
