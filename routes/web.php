<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/books/report', [\App\Http\Controllers\BookController::class,'statReport']);