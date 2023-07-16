<?php

namespace App\Events;

use App\Enums\OtpTypesEnum;
use App\Models\User;

final readonly class OtpIssued
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public User $user,
        public string $code,
        public OtpTypesEnum $type,
    ) {
    }
}
