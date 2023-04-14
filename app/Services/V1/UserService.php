<?php

namespace App\Services\V1;

use App\DTO\V1\RegisterUserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function store(RegisterUserDTO $userDTO): User
    {
        return User::create([
            'name' => $userDTO->name,
            'email' => $userDTO->email,
            'password' => Hash::make($userDTO->password),
        ]);
    }
}
