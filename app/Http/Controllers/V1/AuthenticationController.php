<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\V1\UserRegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $credentials = $request->validated();

        $user = User::create($this->hashCredentialsPassword($credentials)->all());

        /** @var string $token */
        $token = auth()->login($user);

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
