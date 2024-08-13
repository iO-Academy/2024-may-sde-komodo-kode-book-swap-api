<?php

namespace App\Http\Controllers;

use App\Models\Book;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function getAllBooks()
    {
        $books = Book::with('genre:id,name')
            ->get()
            ->makeHidden(['created_at', 'updated_at', 'blurb', 'claimed_by_name', 'page_count', 'year', 'genre_id', 'reviews_id']);

        return response()->json([
            'data' => $books,
            'message' => 'Books successfully retrieved',
        ]);
    }
}