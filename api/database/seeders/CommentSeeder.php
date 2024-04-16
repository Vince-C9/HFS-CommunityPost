<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $article = Article::first()->id;

        Comment::factory()->count(3)->create([
            'article_id'=>$article ?? fake()->randomNumber()
        ]);
    }
}
