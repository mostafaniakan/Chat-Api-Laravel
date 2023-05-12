<?php

namespace Tests\Feature\Models;

use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testInsertData()
    {
 $data=Message::factory()->make()->toArray();

   Message::create($data);
   $this->assertDatabaseHas('Messages',$data);
    }
    public function testMessageRelationshipWithUser(){
        $message=Message::factory()->for(User::factory())->create();
        $this->assertTrue(isset($message->user->id));
    }
    public function testMessageRelationshipWithRoom(){
        $message=Message::factory()->for(Room::factory())->create();
        $this->assertTrue(isset($message->room->id));
    }
}
