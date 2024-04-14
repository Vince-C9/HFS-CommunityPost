<?php

namespace Tests\Feature\Article;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use  App\Models\User;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function __construct() {
        $this->user = User::factory()->create();
    }
    /**
     * An authenticated user can create a new article
     * 
     * @test
     * @group Article
     */

     public function it_allows_authenticated_users_to_create_a_new_article(){
        $response = $this->actingAs($this->user)->post(route('article.create'), [
            'title'=>'Test Article',
            'content'=>'This is my super cool test article!'
        ]);

        $response->assertOk();
        $this->assertCount(1,Article::count());
        $this->assertTrue($this->user->article->first()->title === 'Test Article');
     }


     /**
      * An authenticated user can edit an article owned by them
      *
      * @test
      * @group Article
      */

      public function it_allows_authenticated_users_to_edit_their_articles(){
        
        $article = Article::factory()->create([
            'user_id'=>$this->user->id
        ]);

        $response = $this->actingAs($this->user)->put(route('article.update', [$article->id]), [
            'title'=>'Test Article Update',
            'content'=>'This is my super cool test article! Update'
        ]);

        $response->assertOk();
        $this->assertCount(1,Article::count());
        $this->assertTrue($this->user->article->first()->title === 'Test Article Update');
        $this->assertTrue($this->user->article->first()->content === 'This is my super cool test article! Update');
      }

      /**
      * An authenticated user can delete an article owned by them
      *
      * @test
      * @group Article
      */

      public function it_allows_authenticated_users_to_delete_their_articles(){
        $article = Article::factory()->create([
            'user_id'=>$this->user->id
        ]);

        $response = $this->actingAs($this->user)->delete(route('article.delete', [$article->id]));

        $response->assertOk();
        $this->assertCount(0,Article::count());
      }

      /**
      * An authenticated user can get a list of articles
      *
      * @test
      * @group Article
      */

      public function it_gets_a_list_of_existing_articles(){
        Article::factory()->count(3)->create([
            'user_id'=>$this->user->id
        ]);

        $response = $this->actingAs($this->user)->get(route('article.get'));

        $response->assertOk();
        $this->assertCount(3,Article::count());
      }


      /**
      * An authenticated user can get a specific article
      *
      * @test
      * @group Article
      */

      public function it_gets_a_specified_article(){
        $article = Article::factory()->count(3)->create([
            'user_id'=>$this->user->id
        ]);

        $response = $this->actingAs($this->user)->get(route('article.get', [$article->id]));

        $response->assertOk();
        $this->assertCount(1,Article::count());
        $this->assertTrue($response->content()->article->title === 'Test Article');
      }
}
