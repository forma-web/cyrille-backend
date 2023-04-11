<?php

namespace App\DTO;

use Carbon\CarbonImmutable;

final class UserDTO extends DTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $avatar = null,
        public readonly ?CarbonImmutable $createdAt = null,
        public readonly ?CarbonImmutable $updatedAt = null,
    ) {
    }
}
