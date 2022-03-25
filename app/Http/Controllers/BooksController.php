<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Http;


class BooksController extends Controller
{
    //get books
    public function index()
    {
        //get request to api
        try {
            $result = Http::get('https://www.anapioficeandfire.com/api/books');
            $books = $result->json($key = null);
            //sort
            usort($books, function ($book1, $book2) {
                $publishedTimestamp1 = strtotime($book1['released']);
                $publishedTimestamp2 = strtotime($book2['released']);
                return $publishedTimestamp2 <=> $publishedTimestamp1;
            });

            $bks = [];
            foreach ($books as $key => $book) {
                $book['charactersCount'] = count($book['characters']);
                unset($book['characters']);
                $book['povCharactersCount'] = count($book['povCharacters']);
                unset($book['povCharacters']);
                array_push($bks, $book);
            }
            return $bks;
        } catch (\Throwable $th) {
            $error = [
                'message' => "can't fetch books from the server"
            ];
            return response()->json($error, 500);
        }
    }

    // get a single book
    public function show(Request $request, $id)
    {
        try {
            $result = Http::get('https://www.anapioficeandfire.com/api/books/' . $id);
            $comments = Comment::where('bookId', $id)->get();
            $book = $result->json($key = null);

            $book['charactersCount'] = count($book['characters']);
            unset($book['characters']);
            $book['povCharactersCount'] = count($book['povCharacters']);
            unset($book['povCharacters']);

            return ['book' => $book, 'commentsCount' => $comments->count()];
        } catch (\Throwable $th) {
            $error = [
                'message' => "can't fetch your book from the server"
            ];
            return response()->json($error, 500);
        }
    }
}
