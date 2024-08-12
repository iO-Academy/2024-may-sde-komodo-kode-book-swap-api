<?php

namespace App\Http\Controllers;

use App\Models\Book;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function getAllBooks()
    {
        $books = Book::all();

        return response()->json([
            'message' => 'Books retrieved',
            'status' => 'success',
            'data' => $books
        ]);
    }
}