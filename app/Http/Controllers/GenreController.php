<?php

namespace App\Http\Controllers;

use App\Models\Genre;

class GenreController extends Controller
{
    public function getAllGenres()
    {
        $genres = Genre::all();

        if (! $genres) {
            return response()->json([
                'message' => 'Unexpected error occurred',
            ], 500);
        }

        return response()->json([
            'data' => $genres,
            'message' => 'Genres retrieved',
        ], 200);
    }
}
