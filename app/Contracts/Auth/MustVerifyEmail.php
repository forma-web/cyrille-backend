<?php

namespace App\Contracts\Auth;

interface MustVerifyEmail
{
    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(string $code): void;

    /**
     * Get the email address that should be used for verification.
     */
    public function getEmailForVerification(): string;
}
