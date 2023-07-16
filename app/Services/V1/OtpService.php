<?php

namespace App\Services\V1;

use App\Contracts\CodeGenerator;
use App\Enums\OtpTypesEnum;
use App\Events\OtpIssued;
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
        /** @var $otp Otp */
        $otp = $user->otps()->create([
            'type' => $type->value,
            'code' => $this->codeGenerator->generate(),
            'sent_at' => now(),
            'expires_at' => now()->addSeconds(config('auth.otps.timeout')),
        ]);

        event(new OtpIssued($user, $otp->code, $type));

        return $otp;
    }

    public function verify(User $user, string $code, OtpTypesEnum $type): bool
    {
        $code = $user->otps()
            ->where('code', $code)
            ->where('type', $type->value)
            ->whereNull('verified_at')
            ->whereNull('used_at')
            ->where('expires_at', '>=', now());

        if (! $code->exists()) {
            return false;
        }

        $code->update([
            'verified_at' => now(),
        ]);

        return true;
    }

    public function use(User $user, OtpTypesEnum $type): bool
    {
        $code = $user->otps()
            ->where('type', $type->value)
            ->whereNotNull('verified_at')
            ->whereNull('used_at')
            ->where('expires_at', '>=', now());

        if (! $code->exists()) {
            return false;
        }

        $code->update([
            'used_at' => now(),
        ]);

        return true;
    }

    public function verifyAndUse(User $user, string $code, OtpTypesEnum $type): bool
    {
        $code = $user->otps()
            ->where('code', $code)
            ->where('type', $type->value)
            ->whereNull('verified_at')
            ->whereNull('used_at')
            ->where('expires_at', '>=', now());

        if (! $code->exists()) {
            return false;
        }

        $code->update([
            'verified_at' => now(),
            'used_at' => now(),
        ]);

        return true;
    }

    public function has(User $user, OtpTypesEnum $type): bool
    {
        return $user->otps()
            ->where('type', $type->value)
            ->whereNotNull('verified_at')
            ->whereNotNull('used_at')
            ->exists();
    }
}
