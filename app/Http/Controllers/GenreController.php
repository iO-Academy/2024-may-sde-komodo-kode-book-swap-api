<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Services\InternalServerError;
use App\Services\ServerResponse;

class GenreController extends Controller
{
    public function getAllGenres()
    {
        $genres = Genre::all();

        if (! $genres) {
            return InternalServerError::generate(__METHOD__);
        }

        return ServerResponse::generateResponse('Genres retrieved', 200, $genres);
    }
}
