<?php

namespace App\Contracts\Auth;

interface MustVerifyEmail
{
    /**
     * Determine if the user has verified their email address.
     */
    public function hasVerifiedEmail(): bool;

    /**
     * Mark the given user's email as verified.
     */
    public function markEmailAsVerified(): bool;

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(string $code): void;

    /**
     * Get the email address that should be used for verification.
     */
    public function getEmailForVerification(): string;
}
