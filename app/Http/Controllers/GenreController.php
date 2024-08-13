<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function getAllGenres()
    {
        $genres = Genre::all()->makeHidden(['created_at', 'updated_at']);

        return response()->json(['data' => $genres, 'message' => 'Genres retrieved']);
    }
}
