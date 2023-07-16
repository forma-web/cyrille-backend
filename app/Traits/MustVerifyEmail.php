<?php

namespace App\Traits;

use App\Notifications\VerifyEmailNotification;

trait MustVerifyEmail
{
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
