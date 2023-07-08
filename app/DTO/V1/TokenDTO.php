<?php

namespace App\DTO\V1;

final readonly class TokenDTO extends DTO
{
    public function __construct(
        public string $token,
        public string $token_type,
        public int $ttl,
    ) {
    }

    public static function bearerFactory(string $token): self
    {
        return new self(
            token: $token,
            token_type: 'bearer',
            ttl: config('jwt.ttl') * 60,
        );
    }
}
