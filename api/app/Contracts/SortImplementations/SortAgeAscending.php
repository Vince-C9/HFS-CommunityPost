<?php

namespace App\Contracts\SortImplementations;

use App\Contracts\SortInterface;
use App\Models\Article;

class SortAgeAscending implements SortInterface{

    public function sort(){
        return Article::with(['comment', 'user', 'vote'])
            ->with('comment.user')
            ->with(['comment.replies', 'comment.replies.user'])
            ->orderBy('created_at','ASC')
            ->get();
    }
}