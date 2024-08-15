<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Services\InternalServerError;

class GenreController extends Controller
{
    public function getAllGenres()
    {
        $genres = Genre::all();

        if (! $genres) {
            return InternalServerError::generate(__METHOD__);
        }

        return response()->json([
            'data' => $genres,
            'message' => 'Genres retrieved',
        ], 200);
    }
}
