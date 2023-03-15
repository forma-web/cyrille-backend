<?php

namespace App\Http\Controllers\V1;

use App\Models\Book;
use Illuminate\Contracts\Pagination\CursorPaginator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): CursorPaginator
    {
        return Book::cursorPaginate();
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
    public function show(int $id): Book
    {
        return Book::findOrFail($id);
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
