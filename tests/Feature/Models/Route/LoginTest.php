<?php

namespace Tests\Feature\Models\Route;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Models\MessageTest;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndexMethod()
    {

        $user=User::create([
            'name'=>'mostafa',
            'email'=>'mostafaniakan96@gmail.com',
            'phones' => '09035441578',
            'codes'=>'1234'
        ]);
        //attempt login
//        $response = $this->post(route('LoginUser'),[
//            'phones' => '09035441578',
//        ]);
        //Assert it was successful and a token was received
//        $response->assertStatus(200);
         $token=$user->createToken('Test token')->plainTextToken;

    }

}
