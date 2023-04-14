<?php

namespace App\Services\V1;

use App\DTO\V1\TokenDTO;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class AuthenticationService
{
    public function forceLogin(User $user): TokenDTO
    {
        $token = Auth::login($user);

        return $this->tokenDTOFactory($token);
    }

    public function refresh(): TokenDTO
    {
        $token = Auth::refresh();

        return $this->tokenDTOFactory($token);
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
            expires_in: config('jwt.ttl') * 60,
        );
    }
}
