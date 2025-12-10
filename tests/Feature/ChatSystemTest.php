<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $user1;
    protected $user2;
    protected $chatRoom;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user1 = User::factory()->create(['name' => 'User One']);
        $this->user2 = User::factory()->create(['name' => 'User Two']);
        
        $this->chatRoom = ChatRoom::factory()->create([
            'name' => 'Test Chat Room',
            'type' => 'group',
        ]);
        
        $this->chatRoom->users()->attach([$this->user1->id, $this->user2->id]);
    }

    /** @test */
    public function user_can_send_message_to_chat_room()
    {
        $this->actingAs($this->user1);

        $messageData = [
            'room_id' => $this->chatRoom->id,
            'message' => 'Hello from test!',
        ];

        $response = $this->postJson('/api/v1/chat/send', $messageData);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('chat_messages', [
            'room_id' => $this->chatRoom->id,
            'user_id' => $this->user1->id,
            'message' => 'Hello from test!',
        ]);
    }

    /** @test */
    public function user_can_load_chat_messages()
    {
        $this->actingAs($this->user1);

        ChatMessage::factory()->count(10)->create([
            'room_id' => $this->chatRoom->id,
            'user_id' => $this->user1->id,
        ]);

        $response = $this->getJson("/api/v1/chat/rooms/{$this->chatRoom->id}/messages");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'messages' => [
                         '*' => ['id', 'message', 'user_id', 'created_at']
                     ]
                 ]);
    }

    /** @test */
    public function user_can_join_chat_room()
    {
        $newUser = User::factory()->create();
        $this->actingAs($newUser);

        $response = $this->postJson("/api/v1/chat/rooms/{$this->chatRoom->id}/join");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('chat_room_user', [
            'room_id' => $this->chatRoom->id,
            'user_id' => $newUser->id,
        ]);
    }

    /** @test */
    public function user_can_leave_chat_room()
    {
        $this->actingAs($this->user1);

        $response = $this->postJson("/api/v1/chat/rooms/{$this->chatRoom->id}/leave");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('chat_room_user', [
            'room_id' => $this->chatRoom->id,
            'user_id' => $this->user1->id,
        ]);
    }

    /** @test */
    public function user_can_create_private_chat()
    {
        $this->actingAs($this->user1);

        $response = $this->postJson('/api/v1/chat/rooms/create', [
            'name' => 'Private Chat',
            'type' => 'private',
            'user_ids' => [$this->user2->id],
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('chat_rooms', [
            'name' => 'Private Chat',
            'type' => 'private',
        ]);
    }

    /** @test */
    public function user_can_get_list_of_chat_rooms()
    {
        $this->actingAs($this->user1);

        $response = $this->getJson('/api/v1/chat/rooms');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'rooms' => [
                         '*' => ['id', 'name', 'type', 'users']
                     ]
                 ]);
    }

    /** @test */
    public function user_cannot_send_message_to_room_not_member_of()
    {
        $outsider = User::factory()->create();
        $this->actingAs($outsider);

        $messageData = [
            'room_id' => $this->chatRoom->id,
            'message' => 'Trying to send message',
        ];

        $response = $this->postJson('/api/v1/chat/send', $messageData);

        $response->assertStatus(403); // Forbidden
    }

    /** @test */
    public function ai_assistant_can_reply_to_messages()
    {
        $this->actingAs($this->user1);

        $messageData = [
            'room_id' => $this->chatRoom->id,
            'message' => '@ai What is Laravel?',
        ];

        $response = $this->postJson('/api/v1/chat/send', $messageData);

        $response->assertStatus(200);

        // Check if AI response is created
        $this->assertDatabaseHas('chat_messages', [
            'room_id' => $this->chatRoom->id,
            'is_ai' => true,
        ]);
    }

    /** @test */
    public function user_can_mark_messages_as_read()
    {
        $this->actingAs($this->user1);

        $message = ChatMessage::factory()->create([
            'room_id' => $this->chatRoom->id,
            'user_id' => $this->user2->id,
        ]);

        $response = $this->postJson("/api/v1/chat/messages/{$message->id}/read");

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('chat_message_reads', [
            'message_id' => $message->id,
            'user_id' => $this->user1->id,
        ]);
    }
}
