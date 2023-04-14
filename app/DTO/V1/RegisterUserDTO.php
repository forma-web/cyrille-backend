<?php

namespace App\DTO\V1;

final readonly class RegisterUserDTO extends DTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {
    }
}
