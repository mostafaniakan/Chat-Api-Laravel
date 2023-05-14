<?php

namespace Tests\Feature\Models;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testInsertData()
    {
        $data = User::factory()->make()->toArray();
        User::create($data);
          $this->assertDatabaseHas('users', $data);
    }
public function userRelationshipWithMessage(){
        $count=rand(1,10);

        $user=User::factory()->hasMessages($count)->create();
        $this->assertCount($count,$user->messages);
        $this->assertTrue($user->messages->first() instanceof Message);
}
}
