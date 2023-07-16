<?php

namespace App\Services\V1;

use App\DTO\V1\RegisterUserDTO;
use App\DTO\V1\UpdateUserDTO;
use App\Enums\OtpTypesEnum;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

final readonly class UserService
{
    public function __construct(
        private OtpService $otpService,
    ) {
    }

    public function store(RegisterUserDTO $userDTO): User|null
    {
        $user = User::firstWhere('email', $userDTO->email);

        if ($user === null) {
            $user = User::create([
                'name' => $userDTO->name,
                'email' => $userDTO->email,
                'password' => Hash::make($userDTO->password),
            ]);

            $this->otpService->issue($user, OtpTypesEnum::REGISTER);

            return $user;
        }

        if (! $this->otpService->has($user, OtpTypesEnum::REGISTER)) {
            $user->update([
                'name' => $userDTO->name,
                'password' => Hash::make($userDTO->password),
            ]);

            $this->otpService->issue($user, OtpTypesEnum::REGISTER);

            return $user;
        }

        return null;
    }

    public function check(string $code): bool
    {
        return $this->otpService->verifyAndUse($this->current(), $code, OtpTypesEnum::REGISTER);
    }

    public function update(UpdateUserDTO $userDTO): User
    {
        $user = $this->current();

        if ($userDTO->name !== null) {
            $user->name = $userDTO->name;
        }

        if (
            $userDTO->email !== null &&
            $this->otpService->use($user, OtpTypesEnum::CHANGE_EMAIL)
        ) {
            $user->email = $userDTO->email;
        }

        if ($userDTO->password !== null) {
            $user->password = Hash::make($userDTO->password);
        }

        $user->save();

        return $user;
    }

    public function current(): User
    {
        /** @var $user User */
        $user = Auth::user();

        return $user;
    }

    public function emailVerify(): void
    {
        $this->otpService->issue($this->current(), OtpTypesEnum::CHANGE_EMAIL);
    }

    public function emailCheck(string $code): bool
    {
        return $this->otpService->verify($this->current(), $code, OtpTypesEnum::CHANGE_EMAIL);
    }
}
