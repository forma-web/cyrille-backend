<?php

namespace App\DTO\V1;

final readonly class StoreReviewDTO extends DTO
{
    public function __construct(
        public int $rating,
        public ?string $comment = null,
    ) {
    }
}
