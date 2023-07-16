<?php

namespace App\Listeners;

use App\Events\OtpIssued;
use Illuminate\Contracts\Queue\ShouldQueue;

final readonly class SendEmailVerificationNotification implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(OtpIssued $event): void
    {
        $event->user->sendEmailVerificationNotification($event->code);
    }
}
