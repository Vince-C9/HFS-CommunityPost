<?php

namespace App\Http\Controllers;
use App\Models\Article;
use App\Models\Vote;
use Throwable;
use App\Models\Comment;

use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function store(Request $request, Article $article, Comment $comment){
        try {
            Vote::create([
                'value'=> $request->value,
                'user_id'=>auth('sanctum')->user()->id,
                'comment_id'=>$comment->id
            ]);
            return response()->json([
                'status' => 'vote.store',
                'message' => 'Vote saved.',
            ], 200);
        }
        catch(Throwable $t) {
            return response()->json([
                'status' => $t->getCode(),
                'message' => $t->getMessage(),
            ], 500);
        }

    }
}
