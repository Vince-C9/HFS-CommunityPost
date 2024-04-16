<?php

namespace App\Models;

use App\Contracts\SortImplementations\SortAgeAscending;
use App\Contracts\SortImplementations\SortAgeDescending;
use App\Contracts\SortImplementations\SortVoteAscending;
use App\Contracts\SortImplementations\SortVoteDescending;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'created_at'
    ];

    protected $appends = ['showComments'];

    //Article belongs to user
    public function user(){
        return $this->belongsTo(User::class);
    }

    //Articles have many comments
    public function comment(){
        return $this->hasMany(Comment::class);
    }

    public function vote(){
        return $this->hasMany(Vote::class);
    }

    public static function sort($sortType){
        switch($sortType){
            case 'asc':
                $sortImplementation = new SortAgeAscending();
            break;
            case 'desc':
                $sortImplementation = new SortAgeDescending();
            break;
            case 'votes-asc':
                $sortImplementation = new SortVoteAscending();
            break;
            case 'votes-desc':
                $sortImplementation = new SortVoteDescending();
            break;
            
        }

        return $sortImplementation->sort();
    }

    /**
     * Helper to handle show/hide comments. Default is false, or closed
     */
    public function getShowCommentsAttribute(){
        return $this->attributes['showComments']=false;
    }
}
