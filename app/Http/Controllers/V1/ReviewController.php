<?php

namespace App\Http\Controllers\V1;

use App\DTO\V1\StoreReviewDTO;
use App\Http\Requests\V1\StoreReviewRequest;
use App\Http\Resources\V1\ReviewResource;
use App\Models\Book;
use App\Services\V1\ReviewService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReviewController extends Controller
{
    private const REVIEWS_PER_PAGE = 5;

    public function __construct(
        private readonly ReviewService $reviewService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(int $bookId): AnonymousResourceCollection
    {
        return ReviewResource::collection(
            Book::query()
                ->findOrFail($bookId)
                ->reviews()
                ->orderBy('id', 'desc')
                ->latest()
                ->with('user:id,name,avatar')
                ->cursorPaginate(self::REVIEWS_PER_PAGE)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(int $bookId, StoreReviewRequest $request): ReviewResource
    {
        return ReviewResource::make(
            $this->reviewService->store($bookId,StoreReviewDTO::fromRequest($request))
        );
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        // Not implemented
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        // Not implemented
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        // Not implemented
    }
}
