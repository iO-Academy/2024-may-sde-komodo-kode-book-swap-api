<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/books/{id}', [\App\Http\Controllers\BookController::class, 'getSingleBook']);
Route::put('/books/{id}',[\App\Http\Controllers\BookController::class, 'claimBook']);
Route::get('/books', [\App\Http\Controllers\BookController::class, 'getAllBooks']);

Route::get('/genres', [\App\Http\Controllers\GenreController::class, 'getAllGenres']);
