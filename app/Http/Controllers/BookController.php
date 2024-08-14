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
        $this->book = $book;
    }

    public function getSingleBook(int $id)
    {
       $book=$this->book->with('genre')->with('reviews')->find($id);
       if (!$book) {
           return response()->json([
               'message' => "Book with id {$id} not found"
           ], 404);
       }
       return response()->json([
           'data' => $book,
           'message' => 'Book successfully found',
       ]);
    }

    public function getAllBooks(Request $request)
    {
        $request->validate([
            'claimed' => 'boolean',
            'genre' => 'int|exists:genres,id',
            'search' => 'string'
        ]);

        $books = $this->book->with('genre:id,name');

        if ($request->genre) {
            $books->where('genre_id', '=', $request->genre);
        }

        if ($request->search) {
            $books->whereAny(['title', 'author', 'blurb'], 'LIKE', "%{$request->search}%");
        }

        if($request->claimed == 1){
            $books = $books->where('claimed_by_name','!=',null);
        } else{
            $books = $books->where('claimed_by_name','=',null);
        }

        $books = $books->get()->makeHidden(['created_at', 'updated_at', 'blurb', 'claimed_by_name', 'page_count', 'year', 'genre_id', 'reviews_id']);
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

        if($book->claimed_by_name != null){
            return response()->json([
                'message' => "Book {$id} is claimed"
            ], 400);
        }

        $book->claimed_by_name = $request->claimed_by_name;
        $book->email = $request->email;

        if($book->save()){
            return response()->json([
                'message' => "Book {$id} was claimed"
            ]);
        }

        return response()->json([
            'message' => 'Something went wrong'
        ], 500);
    }

    public function unclaimBook(int $id, Request $request)
    {
        $request->validate([
            'email' => 'string|email|required'
        ]);
        $book = Book::find($id);

        if(!$book) {
            return response()->json([
                'message' => "Book {$id} was not found"
            ], 404);
        }
        if($book->claimed_by_name === null) {
            return response()->json([
                'message' => "Book {$id} is not currently claimed"
            ], 400);
        }
        if($book->email != $request->email) {
            return response()->json([
                'message' => "Book {$id} was not returned. {$request->email} did not claim this book."
            ], 400);
        }

        $book->claimed_by_name = null;
        $book->email = null;

        if($book->save()){
            return response()->json([
                'message' => "Book {$id} was returned"
            ]);
        }

        return response()->json([
            'message' => 'Book {$id} was not able to be returned'
        ], 500);
    }
 
    public function addBook(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:1|max:20',
            'author' => 'required|string|min:1|max:20',
            'genre_id' => 'required|int|exists:genres,id',
            'blurb' => 'string|max:50',
            'image' => 'string|max:250',
            'year' => 'int|digits:4'
        ]);

        $book = new Book();

        $book->title = $request->title;
        $book->author = $request->author;
        $book->genre_id = $request->genre_id;
        $book->blurb = $request->blurb;
        $book->image = $request->image;
        $book->year = $request->year;

        if ($book->save()) {
            return response()->json([
               'message' => 'Book created',
            ], 201);
        }

        return response()->json([
            'message' => 'Unexpected error occurred',
        ], 500);
    }
}