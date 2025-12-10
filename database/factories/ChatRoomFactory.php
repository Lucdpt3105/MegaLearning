<?php

namespace Database\Factories;

use App\Models\ChatRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatRoomFactory extends Factory
{
    protected $model = ChatRoom::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'type' => fake()->randomElement(['group', 'private']),
            'room_type' => fake()->randomElement(['group', 'private', 'subject', 'class']),
            'description' => fake()->sentence(),
            'created_by' => null,
        ];
    }
}
