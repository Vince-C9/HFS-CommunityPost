<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'article_id',
        'value'
    ];

    
    //A vote belongs to a user
    public function user(){
        return $this->belongsTo(User::class);
    }

    //A vote belongs to a comment
    public function article(){
        return $this->belongsTo(Article::class);
    }

}
