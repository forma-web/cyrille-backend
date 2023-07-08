<?php

namespace App\Services\V1;

use App\DTO\V1\StoreReviewDTO;
use App\Models\Book;
use Illuminate\Database\Eloquent\Model;

final class ReviewService
{
    public function store(int $bookId, StoreReviewDTO $reviewDTO): Model
    {
        return Book::query()
            ->findOrFail($bookId)
            ->reviews()
            ->create([
                'user_id' => auth()->id(),
                ...$reviewDTO->toArray(),
            ]);
    }
}
