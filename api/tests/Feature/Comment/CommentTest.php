<?php

namespace Tests\Feature\Comment;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     * @group Comments
     */
    public function it_can_add_a_comment_to_an_article(){
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $response = $this->actingAs($user)->post(route('comment.add', [$article->id]), [
            'comment'=>'My cool comment!'
        ]);

        $response->assertOk();
        $response->assertSee("Comment added");
    }

    /**
     * @test
     * @group Comments
     */
    public function it_can_allow_a_reply_on_a_comment(){
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $comment = Comment::factory()->create([
            'article_id'=>$article->id
        ]);
        $response = $this->actingAs($user)->post(route('comment.reply', [$article->id, $comment->id]), [
            'comment'=>'My cool reply!'
        ]);

        $response->assertOk();
        $response->assertSee("Reply added");
    }


    /**
     * @test
     * @group Comments
     */
    public function it_can_allow_a_user_to_delete_their_own_comment(){
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $comment = Comment::factory()->create([
            'article_id'=>$article->id
        ]);
        $response = $this->actingAs($user)->delete(route('comment.delete', [$article->id, $comment->id]));

        $response->assertOk();
        $response->assertSee("Comment deleted");
    }

    /**
     * @test
     * @group Comments
     */
    public function it_can_allow_a_user_to_update_their_own_comment(){
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $comment = Comment::factory()->create([
            'article_id'=>$article->id,
            'comment'=>'My cool comment'
        ]);
        $response = $this->actingAs($user)->delete(route('comment.delete', [$article->id, $comment->id]), [
            'comment'=> 'My Updated Comment!'
        ]);

        $response->assertOk();
        $response->assertSee("Comment deleted");
        $this->assertTrue($comment->fresh()->comment !== 'My cool comment');
    }

    
}
