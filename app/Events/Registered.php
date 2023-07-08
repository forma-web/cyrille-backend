<?php

namespace App\Events;

use App\Models\User;

final readonly class Registered
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly User $user,
    ) {
    }
}
