<?php

namespace Tests\Feature\Models\Route;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndexMethod()
    {

        $this->withoutExceptionHandling();
        $response = $this->postJson(route('Register'), [
            'name' => fake()->name(),
            'email' =>fake()->email() ,
            'phones'=>fake()->e164PhoneNumber(),
        ])->assertOk();
                $this->assertTrue(isset($response));
    }
}
