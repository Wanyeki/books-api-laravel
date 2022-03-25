<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class CharactersController extends Controller
{
    public function index(Request $req)
    {
        try {
            $url = 'https://www.anapioficeandfire.com/api/characters?';
            $queryParams = $req->query();

            //filter
            foreach ($queryParams as $key => $value) {
                if ($key != 'sortby') {
                    $url = $url . $key . '=' . $value . '&';
                }
            }
            $url = substr($url, 0, -1);
            $result = Http::get($url);
            $characters = $result->json($key = null);

            //sort
            if ($req->query('sortby') != null) {
                usort($characters, function ($character1, $character2) use ($req) {
                    $sortBy = $req->query('sortby');
                    $order = $req->query('order');
                    if ($order == 'desc') {
                        return $character1[$sortBy] <=> $character2[$sortBy];
                    } else {
                        return $character2[$sortBy] <=> $character1[$sortBy];
                    }
                });
            }

            $totalAge = 0;
            $totalHeight = [
                'months' => 0,
                'years' => 0
            ];
            foreach ($characters as $key => $character) {
                //   $totalAge=$totalAge+$character['age'];  
            }
            $metadata = [
                'totalCharacters' => count($characters),
                'totalAge' => $totalAge,
                'totalHeight'=>$totalHeight
            ];

            return ['characters' => $characters, 'metadata' => $metadata];
        } catch (\Throwable $th) {
            $error = [
                'message' => "can't fetch characters from the server"
            ];
            return response()->json($error, 500);
        }

        return [];
    }


    public function show(Request $req, $id)
    {
        try {
            $result = Http::get('https://www.anapioficeandfire.com/api/characters/' . $id);
            $character = $result->json($key = null);

            return $character;
        } catch (\Throwable $th) {
            $error = [
                'message' => "can't fetch your character from the server"
            ];
            return response()->json($error, 500);
        }
    }
}
