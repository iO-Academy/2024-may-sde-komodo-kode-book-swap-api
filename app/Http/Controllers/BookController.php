<?php

namespace App\Http\Controllers;

use App\Models\Book;
use GrahamCampbell\ResultType\Success;
use http\Env\Response;
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
               'message' => "Book with id {$id} not found"
           ], 404);
       }
       return response()->json([
           'data' => $book,
           'message' => 'Book successfully found',
       ]);
    }

    public function getAllBooks()
    {
        $books = $this->book->with('genre:id,name')
            ->get()
            ->makeHidden(['created_at', 'updated_at', 'blurb', 'claimed_by_name', 'page_count', 'year', 'genre_id', 'reviews_id']);

        return response()->json([
            'data' => $books,
            'message' => 'Books successfully retrieved',
        ]);
    }

    public function claimBook(int $id, Request $request)
    {
        $request->validate([
            'claimed_by_name' => 'string|required',
            'email' => 'string|email|required'
        ]);

        $book = Book::find($id);

        if(!$book){
            return response()->json([
                'message' => "Book {$id} was not found"
            ], 404);
        }

        if($book->claimed_by_name!=null){
            return response()->json([
                'message' => "Book {$id} is claimed"
            ], 400);
        }

        $book->claimed_by_name=$request->claimed_by_name;
        $book->email=$request->email;

        if($book->save()){
            return response()->json([
                'message' => "Book {$id} was claimed"
            ]);
        }

        return response()->json([
            'message' => 'Something went wrong'
        ], 500);
    }
}