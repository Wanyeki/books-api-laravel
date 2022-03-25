<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CharactersController;


Route::prefix('v1')->group(function () {
    //books
    Route::get('books', [BooksController::class, 'index']);
    Route::get('books/{id}', [BooksController::class, 'show']);

    //comments
    Route::get('books/{bookId}/comments', [CommentController::class, 'index']);
    Route::get('books/{bookId}/comments/{id}', [CommentController::class, 'show']);
    Route::delete('books/{bookId}/comments/{id}', [CommentController::class, 'delete']);
    Route::patch('books/{bookId}/comments/{id}', [CommentController::class, 'update']);
    Route::post('books/{bookId}/comments', [CommentController::class, 'add']);

    //Characters
    Route::get('characters', [CharactersController::class, 'index']);
    Route::get('characters/{id}', [CharactersController::class, 'show']);
    Route::get('books/{bookId}/characters', [CommentController::class, 'getBookCharacters']);


    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
});
