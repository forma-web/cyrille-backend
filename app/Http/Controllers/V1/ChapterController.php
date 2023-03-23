<?php

namespace App\Http\Controllers\V1;

use App\Http\Resources\V1\ChapterResource;
use App\Models\Book;

class ChapterController extends Controller
{
    public function index(int $bookId)
    {
        return ChapterResource::collection(
            Book::findOrFail($bookId)
                ->chapters()
                ->select('id', 'order')
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
    public function show(int $bookId, int $chapterId)
    {
        return new ChapterResource(
            Book::findOrFail($bookId)
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
