<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'comment',
        'user_id',
        'parent_id',
        'article_id'
    ];

    //Comments belong to articles
    public function article(){
        return $this->belongsTo(Article::class);
    }

    //Comments are written by users
    public function user(){
        return $this->belongsTo(User::class)->select(['id','name']);
    }

    //Replies are replied to comments.
    public function replies(){
        return $this->hasMany(Comment::class, 'parent_id')->with(['replies', 'replies.user']);
    }

    
}
