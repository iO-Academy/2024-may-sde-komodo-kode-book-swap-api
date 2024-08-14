<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(BookController::class)->group(function () {
    Route::get('/books/{id}', 'getSingleBook');
    Route::put('/books/claim/{id}', 'claimBook');
    Route::get('/books', 'getAllBooks');
    Route::post('/books', 'addBook');
    Route::put('/books/return/{id}', 'unclaimBook');
});

Route::get('/genres', [\App\Http\Controllers\GenreController::class, 'getAllGenres']);

Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'createReview']);