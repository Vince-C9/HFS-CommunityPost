<?php

namespace App\Contracts\SortImplementations;

use App\Contracts\SortInterface;
use App\Models\Article;
use Illuminate\Support\Facades\DB;

class SortVoteAscending implements SortInterface{

    public function sort(){
        return Article::with(['comment', 'user', 'vote'])
        ->with('comment.user')
        ->with(['comment.replies', 'comment.replies.user'])
        ->withCount(['vote AS vote_total' => function($q){
            $q->select(DB::raw('IFNULL(SUM(value), 0) AS vote_total'));
        }])->orderBy('vote_total','ASC')
        ->get();
    }
}