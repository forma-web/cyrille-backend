<?php

namespace App\DTO\V1;

final readonly class UpdateUserDTO extends DTO
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?string $password_confirmation = null,
        public ?string $current_password = null,
    ) {
    }
}
