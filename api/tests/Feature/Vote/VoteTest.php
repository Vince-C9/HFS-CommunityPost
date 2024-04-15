<?php

namespace Tests\Feature\Vote;

use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoteTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     * @group Vote
     */

     public function it_can_apply_a_positive_vote_value_to_a_comment(){
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id'=>$user->id
        ]);
        $comment = Comment::factory()->create([
            'user_id'=>$user->id,
            'article_id'=>$article->id
        ]);
        $response = $this->actingAs($user)->post(route('article.comment.add-vote', [$article->id, $comment->id]), [
            'value'=>1
        ]);

        $response->assertOk();
        $response->assertSee(["Vote saved.", 'vote.store']);
     }

     /**
     * @test
     * @group Vote
     */

     public function it_can_apply_a_negative_vote_value_to_a_comment(){
        $user = User::factory()->create();
        $article = Article::factory()->create([
            'user_id'=>$user->id
        ]);
        $comment = Comment::factory()->create([
            'user_id'=>$user->id,
            'article_id'=>$article->id
        ]);
        $response = $this->actingAs($user)->post(route('article.comment.add-vote', [$article->id, $comment->id]), [
            'value'=>-1
        ]);

        $response->assertOk();
        $response->assertSee(["Vote saved.", 'vote.store']);
     }
}
