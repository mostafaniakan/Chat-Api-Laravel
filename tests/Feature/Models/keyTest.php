<?php

namespace Tests\Feature\Models;

use App\Models\Key;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class keyTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testInsertData()
    {
       $data=Key::factory()->make()->toArray();
       Key::create($data);
       $this->assertDatabaseHas('keys',$data);
    }
    public function keyRelationshipWithUser(){
        $key=Key::factory()->for(User::factory()->create());
        $this->assertTrue(isset($key->user->id));
    }
    public function keyRelationshipWithRoom(){
        $key=Key::factory()->for(Room::factory()->create());
        $this->assertTrue(isset($key->room->id));
    }
}
