<?php

namespace App\DTO\V1;

final readonly class LoginUserDTO extends DTO
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }
}
