<?php

namespace App\Http\Controllers;

use App\Models\Book;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function getAllBooks(Request $request)
    {
//        $request->validate([
//            'claimed' => 'integer|min:0|max:1',
//            'genre' => 'exists:genre,id',
//            'search' => 'string'
//        ]);

        $books = Book::with('genre:id,name')
            ->get()
            ->makeHidden(['created_at', 'updated_at', 'blurb', 'claimed_by_name', 'page_count', 'year', 'genre_id', 'reviews_id']);

//        if ($request->claimed == 0) {
//            $books = $books->where('claimed_by_name', '=', NULL)
//                ->get()
//                ->makeHidden(['created_at', 'updated_at', 'blurb', 'claimed_by_name', 'page_count', 'year', 'genre_id', 'reviews_id']);
//        }
//        elseif ($request->claimed == 1) {
//            $books = $books->where('claimed_by_name', '!=', NULL)
//                ->get()
//                ->makeHidden(['created_at', 'updated_at', 'blurb', 'claimed_by_name', 'page_count', 'year', 'genre_id', 'reviews_id']);
//        }
//        if ($request->claimed > 1) {
//            return response()->json([
//                'message' => 'UNPROCESSABLE CONTENT',
//            ], 422);
//        }

        return response()->json([
            'data' => $books,
            'message' => 'Books successfully retrieved',
        ]);
    }
}