<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;

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


    /**
     * @test
     * @group Auth
     */

     public function it_resets_the_password_when_token_email_and_passwords_are_sent(){
        Notification::fake();
        //Make a user who has foolishly forgot their password!
        $user = User::factory()->create();
        $oldPassword = $user->password;

        //Send a forgot password link
        $this->post(route('auth.forgot-password'), [
            'email'=>$user->email,
        ]);

        //Get the token for the user
        $token = DB::table('password_reset_tokens')->select('token')->whereEmail($user->email)->pluck('token')->first();

        $response = $this->post(route('auth.reset-password'), [
            'email'=>$user->email,
            'token'=>$token,
            'password'=>'secret123!',
            'password_confirmation'=>'secret123!'
        ]);

        $response->assertOk();
        $response->assertSee(['passwords.token', 'Password reset successfully']);
        Notification::assertCount(1);
     }
}
