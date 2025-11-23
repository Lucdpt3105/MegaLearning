<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Services\AIService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AIServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $aiService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->aiService = app(AIService::class);
    }

    /** @test */
    public function it_can_check_if_openai_is_configured()
    {
        $isConfigured = $this->aiService->isConfigured();
        
        // Depends on .env OPENAI_API_KEY
        $this->assertIsBool($isConfigured);
    }

    /** @test */
    public function it_can_create_ai_user()
    {
        $aiUser = $this->aiService->getAIUser();
        
        $this->assertNotNull($aiUser);
        $this->assertEquals('ai@megalearning.local', $aiUser->email);
        $this->assertTrue($aiUser->hasRole('ai'));
    }

    /** @test */
    public function it_should_respond_when_mentioned()
    {
        $user = User::factory()->create();
        $room = ChatRoom::factory()->create();
        
        $message = ChatMessage::create([
            'room_id' => $room->room_id,
            'user_id' => $user->id,
            'message_text' => 'Hey AI, can you help me?',
            'message_type' => 'text'
        ]);

        $shouldRespond = $this->invokeMethod(
            $this->aiService, 
            'shouldRespond', 
            [$message]
        );

        $this->assertTrue($shouldRespond);
    }

    /** @test */
    public function it_should_respond_to_questions()
    {
        $user = User::factory()->create();
        $room = ChatRoom::factory()->create();
        
        $message = ChatMessage::create([
            'room_id' => $room->room_id,
            'user_id' => $user->id,
            'message_text' => 'What is Laravel?',
            'message_type' => 'text'
        ]);

        $shouldRespond = $this->invokeMethod(
            $this->aiService, 
            'shouldRespond', 
            [$message]
        );

        $this->assertTrue($shouldRespond);
    }

    /** @test */
    public function it_can_build_conversation_context()
    {
        $user = User::factory()->create(['name' => 'Alice']);
        $room = ChatRoom::factory()->create();

        // Create some messages
        ChatMessage::create([
            'room_id' => $room->room_id,
            'user_id' => $user->id,
            'message_text' => 'Hello everyone!',
            'message_type' => 'text'
        ]);

        ChatMessage::create([
            'room_id' => $room->room_id,
            'user_id' => $user->id,
            'message_text' => 'Anyone here?',
            'message_type' => 'text'
        ]);

        $context = $this->invokeMethod(
            $this->aiService,
            'buildConversationContext',
            [$room, 10]
        );

        $this->assertIsArray($context);
        $this->assertCount(2, $context);
        $this->assertEquals('user', $context[0]['role']);
        $this->assertStringContainsString('Alice:', $context[0]['content']);
    }

    /** @test */
    public function it_can_build_system_prompt()
    {
        $room = ChatRoom::factory()->create([
            'room_name' => 'Test Room',
            'room_type' => 'group'
        ]);

        $prompt = $this->invokeMethod(
            $this->aiService,
            'buildSystemPrompt',
            [$room]
        );

        $this->assertStringContainsString('AI chat participant', $prompt);
        $this->assertStringContainsString('Test Room', $prompt);
        $this->assertStringContainsString('E-Learning platform', $prompt);
    }

    /**
     * Helper method to invoke protected methods
     */
    protected function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
