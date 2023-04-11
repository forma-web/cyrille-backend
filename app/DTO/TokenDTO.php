<?php

namespace App\DTO;

class TokenDTO extends DTO
{
    public function __construct(
        public string $token,
        public string $token_type,
        public int $expires_in,
    ) {
    }
}
