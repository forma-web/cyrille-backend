<?php

namespace App\Services\V1;

use App\DTO\V1\LoginUserDTO;
use App\DTO\V1\TokenDTO;
use App\Enums\OtpTypesEnum;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final readonly class AuthenticationService
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

    public function resetPassword(string $email): void
    {
        $user = User::firstWhere('email', $email);

        if ($user) {
            $otp = app(OtpService::class)->issue($user, OtpTypesEnum::RESET_PASSWORD);

            if ($otp) {
                $user->sendEmailVerificationNotification($otp->code);
            }
        }
    }

    public function resetPasswordVerify(string $code, string $email, string $password): void
    {
        $user = User::firstWhere('email', $email);

        if ($user) {
            $otp = app(OtpService::class)->verify($user, OtpTypesEnum::RESET_PASSWORD, $code);

            if ($otp) {
                $user->update([
                    'password' => \Hash::make($password),
                ]);

                return;
            }
        }

        abort(Response::HTTP_UNAUTHORIZED);
    }
}
