<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

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
        //dd($user->password, $user->email,  User::whereEmail($user->email)->first()->password, User::whereEmail($user->email)->first()->email);

        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        

        $response->assertOk();
        $response->assertSee(['status','message','token']);
    }

}
