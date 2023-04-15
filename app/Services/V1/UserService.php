<?php

namespace App\Services\V1;

use App\DTO\V1\RegisterUserDTO;
use App\DTO\V1\UpdateUserDTO;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

final class UserService
{
    public function store(RegisterUserDTO $userDTO): User
    {
        $user = User::create([
            'name' => $userDTO->name,
            'email' => $userDTO->email,
            'password' => Hash::make($userDTO->password),
        ]);

        event(new Registered($user));

        return $user;
    }

    public function update(int $userId, UpdateUserDTO $userDTO): User
    {
        $user = User::findOrFail($userId);

        $user->update([
            'name' => $userDTO->name,
            'email' => $userDTO->email,
        ]);

        return $user;
    }

    public function current(): Authenticatable|null
    {
        return Auth::user();
    }
}
