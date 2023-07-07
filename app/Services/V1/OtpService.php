<?php

namespace App\Services\V1;

use App\Contracts\CodeGenerator;
use App\Enums\OtpTypesEnum;
use App\Models\Otp;
use App\Models\User;

final readonly class OtpService
{
    public function __construct(
        private CodeGenerator $codeGenerator,
    ) {
    }

    public function issue(User $user, OtpTypesEnum $type): ?Otp
    {
        if ($this->hasActive($user, $type)) {
            return null;
        }

        /** @var $otp Otp */
        $otp = $user->otps()->create([
            'type' => $type->value,
            'code' => $this->codeGenerator->generate(),
            'sent_at' => now(),
            'expires_at' => now()->addSeconds(config('auth.password_timeout')),
        ]);

        return $otp;
    }

    public function verify(User $user, OtpTypesEnum $type, string $code): bool
    {
        return $user->otps()
            ->where('type', $type->value)
            ->where('code', $code)
            ->where('expires_at', '>=', now())
            ->exists();
    }

    private function hasActive(User $user, OtpTypesEnum $type): bool
    {
        return $user->otps()
            ->where('type', $type->value)
            ->where('expires_at', '>', now())
            ->exists();
    }
}
