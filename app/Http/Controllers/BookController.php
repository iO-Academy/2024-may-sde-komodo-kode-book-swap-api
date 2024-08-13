<?php

namespace App\Http\Controllers;

use App\Models\Book;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private Book $book;

    public function __construct(Book $book)
    {
        $this->book=$book;
    }

    public function getSingleBook(int $id)
    {
       $book=$this->book->with('genre')->with('reviews')->find($id);
       if (!$book) {
           return response()->json([
               'message'=>"Book with id {$id} not found"
           ], 404);
       }
       return response()->json([
           'data'=>$book,
           'message'=>'Book successfully found',
       ]);
    }

    public function getAllBooks(Request $request)
    {
        $books = $this->book->with('genre:id,name');

        if ($request->genre) {
            $books->where('genre_id', '=', $request->genre);
        }
        if ($request->genre > 10) {
            return response()->json([
                'message' => 'The selected genre is invalid.',
            ], 422);
        }

        $books = $books
            ->get()
            ->makeHidden(['created_at', 'updated_at']);

        return response()->json([
            'data' => $books,
            'message' => 'Books successfully retrieved',
        ]);
    }
}