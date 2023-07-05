<?php

namespace App\Http\Controllers\V1;

use App\Http\Resources\V1\BookResource;
use App\Models\Book;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return BookResource::collection(
            Book::query()
                ->with('authors')
                ->cursorPaginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        // Not implemented
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): BookResource
    {
        return new BookResource(
            Book::query()
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->with('authors', 'artists')
                ->findOrFail($id)
        );
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
