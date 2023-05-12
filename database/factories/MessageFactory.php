<?php

namespace Database\Factories;

use App\Models\Room;

use App\Models\User;
use http\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>

 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'messages'=>fake()->text(),
            'user_id'=> User::factory(),
            'room_id'=>Room::factory(),
            'images'=>fake()->imageUrl(),
        ];
    }
}
