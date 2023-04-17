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

        // TODO: Use a DTO with mass assignment

        if ($userDTO->name !== null) {
            $user->name = $userDTO->name;
        }

        if ($userDTO->email !== null) {
            $user->email = $userDTO->email;
        }

        $user->save();

        return $user;
    }

    public function updatePassword(int $userId, string $password): User
    {
        $user = User::findOrFail($userId);

        $user->update([
            'password' => Hash::make($password),
        ]);

        return $user;
    }

    public function current(): Authenticatable|null
    {
        return Auth::user();
    }
}
