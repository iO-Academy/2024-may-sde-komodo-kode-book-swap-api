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
       if (!$book){
           return response()->json([
               'message'=>"Book with id {$id} not found"
           ], 404);
       }
       return response()->json([
           'data'=>$book,
           'message'=>'Book successfully found',
       ]);
    }

    public function getAllBooks($genre)
    {
        $books = $this->book->with('genre:id,name')
            ->get()
            ->makeHidden(['created_at', 'updated_at', 'blurb', 'claimed_by_name', 'page_count', 'year', 'genre_id', 'reviews_id']);

        if ($genre) {

            $books = Book::where('genre_id', '==', $genre);
            return response()->json([
                'data'=>$books,
                'message' => 'Books successfully retrieved',
            ], 200);
        }

        return response()->json([
            'data' => $books,
            'message' => 'Books successfully retrieved',
        ]);

    }
}