<?php

namespace App\Services\V1;

use App\DTO\TokenDTO;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(User $user): TokenDTO
    {
        $token = Auth::login($user);

        return new TokenDTO(
            token: $token,
            token_type: 'bearer',
            expires_in: config('jwt.ttl') * 60,
        );
    }
}
