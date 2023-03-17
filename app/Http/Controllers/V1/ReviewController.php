<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\V1\StoreReviewRequest;
use App\Models\Book;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $bookId)
    {
        return Book::findOrFail($bookId)
            ->reviews()
            ->with('user:id,name,avatar')
            ->cursorPaginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(int $bookId, StoreReviewRequest $request)
    {
        $review = $request->validated();

        $review->put('user_id', auth()->id());

        Book::findOrFail($bookId)
            ->reviews()
            ->create($review->all());
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
