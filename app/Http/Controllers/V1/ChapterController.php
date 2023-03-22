<?php

namespace App\Http\Controllers\V1;

use App\Models\Book;

class ChapterController extends Controller
{
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
        return Book::findOrFail($bookId)
            ->chapters()
            ->findOrFail($chapterId);
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
