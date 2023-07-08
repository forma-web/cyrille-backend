<?php

namespace App\Services\V1;

use App\DTO\V1\RegisterUserDTO;
use App\DTO\V1\UpdateUserDTO;
use App\Enums\OtpTypesEnum;
use App\Events\Registered;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

final readonly class UserService
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

    public function current(): User
    {
        /** @var $user User */
        $user = Auth::user();

        return $user;
    }

    public function verify(string $code): void
    {
        $valid = app(OtpService::class)->verify($this->current(), OtpTypesEnum::REGISTER, $code);

        abort_if(! $valid, 403, __('auth.otp.invalid'));
    }
}
