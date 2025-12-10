<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\ChatRoom;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ChatUITest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_send_and_receive_messages_in_chat()
    {
        $user1 = User::factory()->create(['name' => 'User One']);
        $user2 = User::factory()->create(['name' => 'User Two']);

        $chatRoom = ChatRoom::factory()->create([
            'name' => 'Test Chat',
            'type' => 'group',
        ]);

        $chatRoom->users()->attach([$user1->id, $user2->id]);

        $this->browse(function (Browser $first, Browser $second) use ($user1, $user2, $chatRoom) {
            // User 1 sends message
            $first->loginAs($user1)
                  ->visit('/chat')
                  ->clickLink($chatRoom->name)
                  ->type('message', 'Hello from User One!')
                  ->press('Gửi')
                  ->assertSee('Hello from User One!');

            // User 2 receives message
            $second->loginAs($user2)
                   ->visit('/chat')
                   ->clickLink($chatRoom->name)
                   ->waitForText('Hello from User One!', 5)
                   ->assertSee('Hello from User One!');
        });
    }

    /** @test */
    public function user_can_create_new_chat_room()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/chat')
                    ->press('Tạo phòng chat')
                    ->type('room_name', 'New Chat Room')
                    ->select('type', 'group')
                    ->press('Tạo')
                    ->waitForText('New Chat Room')
                    ->assertSee('New Chat Room');
        });
    }

    /** @test */
    public function chat_displays_user_avatars_and_names()
    {
        $user = User::factory()->create(['name' => 'John Doe']);

        $chatRoom = ChatRoom::factory()->create();
        $chatRoom->users()->attach($user->id);

        $this->browse(function (Browser $browser) use ($user, $chatRoom) {
            $browser->loginAs($user)
                    ->visit('/chat')
                    ->clickLink($chatRoom->name)
                    ->type('message', 'Test message')
                    ->press('Gửi')
                    ->assertSee('John Doe')
                    ->assertPresent('.user-avatar');
        });
    }

    /** @test */
    public function ai_assistant_responds_when_tagged()
    {
        $user = User::factory()->create();

        $chatRoom = ChatRoom::factory()->create();
        $chatRoom->users()->attach($user->id);

        $this->browse(function (Browser $browser) use ($user, $chatRoom) {
            $browser->loginAs($user)
                    ->visit('/chat')
                    ->clickLink($chatRoom->name)
                    ->type('message', '@ai What is Laravel?')
                    ->press('Gửi')
                    ->waitForText('AI Assistant', 10)
                    ->assertSee('Laravel')
                    ->assertSee('framework');
        });
    }

    /** @test */
    public function user_can_scroll_through_message_history()
    {
        $user = User::factory()->create();

        $chatRoom = ChatRoom::factory()->create();
        $chatRoom->users()->attach($user->id);

        // Create 20 messages
        for ($i = 1; $i <= 20; $i++) {
            \App\Models\ChatMessage::create([
                'room_id' => $chatRoom->id,
                'user_id' => $user->id,
                'message' => "Message $i",
            ]);
        }

        $this->browse(function (Browser $browser) use ($user, $chatRoom) {
            $browser->loginAs($user)
                    ->visit('/chat')
                    ->clickLink($chatRoom->name)
                    ->assertSee('Message 20');
            
            // Scroll to top to see older messages
            $browser->pause(1000)
                    ->assertSee('Message 1');
        });
    }

    /** @test */
    public function unread_message_badge_updates_correctly()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $chatRoom = ChatRoom::factory()->create();
        $chatRoom->users()->attach([$user1->id, $user2->id]);

        $this->browse(function (Browser $first, Browser $second) use ($user1, $user2, $chatRoom) {
            // User 2 sends a message
            $second->loginAs($user2)
                   ->visit('/chat')
                   ->clickLink($chatRoom->name)
                   ->type('message', 'Hey there!')
                   ->press('Gửi');

            // User 1 should see unread badge
            $first->loginAs($user1)
                  ->visit('/chat')
                  ->assertPresent('.unread-badge')
                  ->clickLink($chatRoom->name)
                  ->pause(1000)
                  ->assertMissing('.unread-badge'); // Badge disappears after viewing
        });
    }
}
