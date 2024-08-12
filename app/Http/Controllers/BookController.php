<?php

namespace App\Http\Controllers;

use App\Models\Book;
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
               'message'=>'book not found',
               'success'=>false], 400);
       }
       return response()->json([
           'message'=>'book found',
           'success'=>true,
           'data'=>$book
       ]);
    }
}
