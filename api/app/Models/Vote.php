<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'comment_id',
        'value'
    ];

    
    //A vote belongs to a user
    public function user(){
        return $this->belongsTo(User::class);
    }

    //A vote belongs to a comment
    public function comment(){
        return $this->belongsTo(Comment::class);
    }

}
