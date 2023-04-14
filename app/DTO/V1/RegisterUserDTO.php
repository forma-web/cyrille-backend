<?php

namespace App\DTO\V1;

use Carbon\CarbonImmutable;

final class RegisterUserDTO extends DTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
    ) {
    }
}
