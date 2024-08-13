<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function getAllGenres()
    {
        $genres = Genre::all()->makeHidden(['created_at', 'updated_at']);

        if (!$genres) {
            return response()->json([
                'message' => 'Unexpected error occurred'
            ], 500);
        }

        return response()->json([
            'data' => $genres,
            'message' => 'Genres retrieved'
        ], 200);

    }
}
