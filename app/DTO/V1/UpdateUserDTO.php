<?php

namespace App\DTO\V1;

final readonly class UpdateUserDTO extends DTO
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
    ) {
    }
}
