<?php

namespace App\Contracts\SortImplementations;

use App\Contracts\SortInterface;
use Illuminate\Support\Facades\DB;
use App\Models\Article;
class SortVoteDescending implements SortInterface{

    public function sort(){
        return Article::with(['comment', 'user', 'vote'])
        ->with('comment.user')
        ->with('comment.replies')
        ->withCount(['vote AS vote_total' => function($q){
            $q->select(DB::raw('IFNULL(SUM(value), 0) AS vote_total'));
        }])->orderBy('vote_total','DESC')
        ->get();
    }
}