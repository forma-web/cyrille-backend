<?php

namespace App\Services\V1;

use App\DTO\V1\LoginUserDTO;
use App\DTO\V1\TokenDTO;
use App\Enums\OtpTypesEnum;
use App\Models\User;
use Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

final readonly class AuthenticationService
{
    public function __construct(
        private OtpService $otpService,
    ) {
    }

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

    public function passwordVerify(string $email): void
    {
        $user = User::firstWhere('email', $email);

        if ($user) {
            $this->otpService->issue($user, OtpTypesEnum::RESET_PASSWORD);
        }
    }

    public function passwordCheck(string $email, string $code): bool
    {
        $user = User::firstWhere('email', $email);

        if (
            $user !== null &&
            $this->otpService->verify($user, $code, OtpTypesEnum::RESET_PASSWORD)
        ) {
            return true;
        }

        return false;
    }

    public function passwordReset(string $email, string $password): bool
    {
        $user = User::firstWhere('email', $email);

        if (
            $user !== null &&
            $this->otpService->use($user, OtpTypesEnum::RESET_PASSWORD)
        ) {
            $user->update([
                'password' => Hash::make($password),
            ]);

            return true;
        }

        return false;
    }
}
