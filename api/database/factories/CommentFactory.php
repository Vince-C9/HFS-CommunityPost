<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Comment;
use App\Models\Article;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'comment' => fake()->sentence(),
            'user_id'=> User::inRandomOrder()->first()->id,
            'parent_id' => Comment::inRandomOrder()->first()->id ?? null,
            'article_id' => Article::inRandomOrder()->first()->id,
        ];
    }
}
