<?php

namespace App\Http\Controllers;

use App\Http\Requests\Article\DestroyArticleRequest;
use App\Http\Requests\Article\StoreArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Throwable;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $articles = Article::with('comment')->get();

        return response()->json([
            'status' => 'article.get',
            'articles' => !empty($articles) ? $articles : [],
            'message' => 'Fetched article list.',
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        try{
            $article = Article::create([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'user_id' => auth('sanctum')->user()->id
            ]);

            return response()->json([
                'status' => 'article.get',
                'article' => $article->id,
                'message' => 'Article saved.',
            ], 200);
        }
        catch(Throwable $t){
            return response()->json([
                'status' => $t->getCode(),
                'message' => $t->getMessage(),
            ], 500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        try {
            return response()->json([
                'status' => 'article.show',
                'article' => $article,
                'message' => 'Fetched article.',
            ], 200); 

        } catch(Throwable $t) {
            return response()->json([
                'status' => $t->getCode(),
                'message' => $t->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        try {
            $article->update([
                'title' => $request->input('title'),
                'content' => $request->input('content')
            ]);

            return response()->json([
                'status' => 'article.update',
                'message' => 'Article Updated.',
            ], 200); 
        } catch(Throwable $t) {
            return response()->json([
                'status' => $t->getCode(),
                'message' => $t->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        try {
            $article->delete();

            return response()->json([
                'status' => 'article.update',
                'message' => 'Article deleted.',
            ], 200); 

        } catch(Throwable $t) {
            return response()->json([
                'status' => $t->getCode(),
                'message' => $t->getMessage(),
            ], 500);
        }
        
    }
}
