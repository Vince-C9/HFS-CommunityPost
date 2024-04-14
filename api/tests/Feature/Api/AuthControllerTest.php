<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     * @group Auth
     */

     public function it_can_create_a_new_user_using_sanctum(){
        
        $response = $this->post(route('auth.register'), [
            'name'=>'test user',
            'email'=>'test@test.com',
            'password'=>'secret'
        ]);

        $response->assertOk();
        $response->assertSee(['status','message','token']);
        
     }

     /**
     * @test
     * @group Auth
     */
    public function it_can_login_with_an_existing_user_created_using_sanctum(){
        $user = User::factory()->create([
                'password'=>Hash::make('secret')
            ]
        );

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $response->assertOk();
        $response->assertSee(['status','message','token']);
    }

    /**
     * @test
     * @group Auth
    */
    public function it_sends_a_forgot_password_email_to_the_email_provided(){
        Notification::fake();

        $user = User::factory()->create();
        $response = $this->post(route('auth.forgot-password'), [
            'email'=>$user->email,
        ]);

        $response->assertOk();
        $response->assertSee(['passwords.sent', 'Password reset link sent']);
        Notification::assertCount(1);
    }
}
