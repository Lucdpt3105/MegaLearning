<?php

namespace Database\Factories;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatMessageFactory extends Factory
{
    protected $model = ChatMessage::class;

    public function definition(): array
    {
        return [
            'room_id' => ChatRoom::factory(),
            'user_id' => User::factory(),
            'message' => fake()->sentence(),
            'is_ai' => false,
            'parent_id' => null,
        ];
    }
}
