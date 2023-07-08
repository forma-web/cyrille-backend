<?php

namespace App\Listeners;

use App\Contracts\Auth\MustVerifyEmail;
use App\Enums\OtpTypesEnum;
use App\Events\Registered;
use App\Services\V1\OtpService;

final readonly class SendEmailVerificationNotification
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private OtpService $otpService,
    ) {
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        if ($event->user instanceof MustVerifyEmail && ! $event->user->hasVerifiedEmail()) {
            $otp = $this->otpService->issue($event->user, OtpTypesEnum::REGISTER);

            if ($otp) {
                $event->user->sendEmailVerificationNotification($otp->code);
            }
        }
    }
}
