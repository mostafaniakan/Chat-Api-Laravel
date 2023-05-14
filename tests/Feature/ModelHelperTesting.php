<?php
namespace tests\Feature\Models;
use App\Models\Message;

trait ModelHelperTesting
{
    public function testInsertData()
    {
        $data=Message::factory()->make()->toArray();

        Message::create($data);
        $this->assertDatabaseHas('Messages',$data);
    }
}
