<?php

namespace Tests\Feature\Models;

use App\Models\Key;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testInserData()
    {
     $room=Room::factory()->make()->toArray();
     Room::create($room);

     $this->assertDatabaseHas('rooms',$room);
    }
    public function roomRelationshipWithUser(){
        $room=Room::factory()->for(User::factory())->create();
        $this->assertTrue(isset($room->user->id));
    }
    public function roomRelationshipWithMessage(){
        $room=Room::factory()->for(Message::factory())->create();
        $this->assertTrue(isset($room->message->id));
        $this->assertTrue($room->message instanceof Message);
    }
    public function roomRelationshipWithKey(){
        $room=Room::factory()->for(Key::factory())->create();
        $this->assertTrue(isset($room->key->id));
        $this->assertTrue($room->key instanceof Key);
    }
}
