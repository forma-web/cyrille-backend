<?php

namespace App\Traits;

use App\Enums\OtpTypesEnum;
use App\Notifications\VerifyEmailNotification;

trait MustVerifyEmail
{
    /**
     * Determine if the user has verified their email address.
     */
    public function hasVerifiedEmail(): bool
    {
        return $this->otps()
            ->where('type', OtpTypesEnum::REGISTER)
            ->whereNotNull('verified_at')
            ->exists();
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(string $code): void
    {
        $this->notify(new VerifyEmailNotification($code));
    }

    /**
     * Get the email address that should be used for verification.
     */
    public function getEmailForVerification(): string
    {
        return $this->email;
    }
}
