<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    // get all comments for a book
    public function index(Request $req, $bookId)
    {
        try {
            $comments = Comment::where('bookId', $bookId)->get();
            return $comments;
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    // get a single comment for a book
    public function show(Request $req, $bookId, $id)
    {
        try {
            $comment = Comment::where('id', $id)->where('bookId', $bookId)->get();
            if ($comment->count() == 0) {
                return response()->json(['message' => 'Comment not found'], 404);
            }
            return $comment[0];
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    //create a comment
    public function add(Request $req, $bookId)
    {
        try {
            try {
                $validated = $this->validate($req, [
                    'commenterName' => ['required', 'max:255'],
                    'comment' => ['required', 'max:500'],
                ]);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 400);
            }

            $ip = $req->ip();
            $comment = Comment::create([
                'commenterName' => $req->commenterName,
                'comment' => $req->comment,
                'ipAddress' => $ip,
                'bookId' => $bookId
            ]);
            return ['message' => 'Comment Created Successfully', 'comment' => $comment];
        } catch (\Throwable $th) {
            return $th;
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    //update comment function
    public function update(Request $req, $bookId, $id)
    {
        try {
            try {
                $validated = $this->validate($req, [
                    'commenterName' => ['required', 'max:255'],
                    'comment' => ['required', 'max:500'],
                ]);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()], 400);
            }

            try {
                $comments = Comment::where('id', $id)->where('bookId', $bookId)->get();
                $comment = $comments[0];
            } catch (\Throwable $th) {
                return response()->json(['message' => 'Comment not found'], 404);
            }


            $comment->commenterName = $req->commenterName;
            $comment->comment = $req->comment;
            $comment->ipAddress = $req->ip();

            $comment->save();

            return ['Message' => 'Comment Updated Successfully', 'comment' => $comment];
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    //delete comment function
    public function delete(Request $req, $bookId, $id)
    {
        try {
            try {
                $comments = Comment::where('id', $id)->where('bookId', $bookId)->get();
                $comment = $comments[0];
            } catch (\Throwable $th) {
                return response()->json(['message' => 'Comment not found'], 404);
            }
            $comment->delete();
            return ['Message' => 'Comment Deleted Successfully'];
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Server error'], 500);
        }
    }
}
