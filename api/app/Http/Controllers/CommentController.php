<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CommentOwnershipRequest;
use App\Http\Requests\Comment\CommentOwnershipDeleteRequest;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Throwable;

class CommentController extends Controller
{
    public function index(Article $article){
        return response()->json([
            'status' => 'comments.get',
            'comments' => $article->comments->get(),
            'message' => 'Article comments retrieved.',
        ], 200);
    }

    public function store(Request $request, Article $article){
        try {
            Comment::create([
                'comment'=> $request->body,
                'user_id'=>auth('sanctum')->user()->id,
                'article_id'=>$article->id
            ]);
            return response()->json([
                'status' => 'comment.store',
                'message' => 'Comment saved.',
            ], 200);
        }
        catch(Throwable $t) {
            return response()->json([
                'status' => $t->getCode(),
                'message' => $t->getMessage(),
            ], 500);
        }
    }

    public function reply(Request $request, Article $article, Comment $comment){
        try {
            Comment::create([
                'comment'=> $request->body,
                'parent_id'=>$comment->id,
                'user_id'=>auth('sanctum')->user()->id,
                'article_id'=>$article->id
            ]);
            return response()->json([
                'status' => 'comment.reply',
                'message' => 'Comment reply added.',
            ], 200);
        }
        catch(Throwable $t) {
            return response()->json([
                'status' => $t->getCode(),
                'message' => $t->getMessage(),
            ], 500);
        }
    }

    public function update(CommentOwnershipRequest $request, Article $article, Comment $comment){
        try {
            $comment->update([
                'body' => $request->comment
            ]);
            return response()->json([
                'status' => 'comment.update',
                'message' => 'Comment updated.',
                'article_id'=>$article->id,
            ], 200);
        }
        catch(Throwable $t) {
            return response()->json([
                'status' => $t->getCode(),
                'message' => $t->getMessage(),
            ], 500);
        }
    }

    public function destroy(CommentOwnershipDeleteRequest $request, Article $article, Comment $comment){

        try {
            $comment->delete();
            return response()->json([
                'status' => 'comment.delete',
                'message' => 'Comment deleted.',
                'article_id'=>$article->id,
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
