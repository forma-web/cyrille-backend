<?php

namespace App\Http\Controllers\V1;

use App\Http\Resources\V1\ChapterResource;
use App\Models\Book;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChapterController extends Controller
{
    public function index(int $bookId): AnonymousResourceCollection
    {
        return ChapterResource::collection(
            Book::query()
                ->findOrFail($bookId)
                ->chapters()
                ->select('id', 'order', 'name', 'content_length')
                ->orderBy('order')
                ->get()
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
    public function show(int $bookId, int $chapterId): ChapterResource
    {
        return ChapterResource::make(
            Book::query()
                ->findOrFail($bookId)
                ->chapters()
                ->findOrFail($chapterId)
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
