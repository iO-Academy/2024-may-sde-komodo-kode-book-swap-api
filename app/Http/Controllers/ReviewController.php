<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function createReview(Request $request)
    {
        $request->validate([
            'name'=> 'required|string',
            'rating'=> 'required|integer|min:0|max:5',
            'review'=> 'required|string',
            'book_id' => 'required|integer|exists:books,id'
        ]);

        $review = new Review();
        $review->name = $request->name;
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->book_id = $request->book_id;

        if($review->save()) {
            return response()->json([
                'message' => 'Review Created'
            ],201);
        }
        return response()->json([
            'message' => 'Unexpected error occurred'
        ],500);
    }
}
