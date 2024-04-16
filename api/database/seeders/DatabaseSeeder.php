<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Create test user
        $user = User::factory()->create([
            'name'=>'Test Testerson',
            'email'=>'test@test.com',
            'password'=>'secret',
        ]);

        //Create 2 other users for the purpose of seeding
        User::factory()->count(2)->create();

        //create 10 articles within the last 4 days
        Article::factory()->count(1)->create([
            'user_id'=>$user->id,
        ]);
        Article::factory()->count(9)->create();
        $article = Article::first()->id;
        
        //Create 3 comments on the fisrt article, made by our user.
        Comment::factory()->count(3)->create(['article_id'=>  $article, 'parent_id'=>null]);
        //Add 6 replies to the first comment.
        $comment = Comment::first();
        Comment::factory()->count(6)->create([
            'parent_id'=>$comment->id,
            'article_id'=>$article,
        ]);

        $comment = Comment::whereNotNull('parent_id')->first();
        //Add a reply to the last reply, on the first comment.
        Comment::factory()->create([
            'parent_id'=>$comment->id,
            'article_id'=>$article,
        ]);

        //Smack some votes on the first article
        Vote::factory()->count(19)->create([
            'article_id'=>Article::first(),
        ]);
        //Make one of the votes from our guy.
        Vote::factory()->create([
            'user_id'=>$user->id,
            'article_id'=> $article
        ]);


        //Add some general fluff
        Comment::factory()->count(25)->create();
        Vote::factory()->count(100)->create();
    }
}
