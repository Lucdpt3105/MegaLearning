<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\User;
use App\Services\AIService;

class TestAIChat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:test-ai {message?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test AI chat response with a sample message';

    protected $aiService;

    /**
     * Execute the console command.
     */
    public function handle(AIService $aiService)
    {
        $this->aiService = $aiService;

        // Check if OpenAI is configured
        if (!$this->aiService->isConfigured()) {
            $this->error('❌ OpenAI is not configured!');
            $this->info('Please set OPENAI_API_KEY in your .env file');
            return 1;
        }

        $this->info('✅ OpenAI is configured');
        $this->info('🤖 AI Name: ' . config('services.openai.ai_name'));
        $this->info('📦 Model: ' . config('services.openai.model'));
        $this->newLine();

        // Get or create test room
        $room = ChatRoom::firstOrCreate([
            'room_name' => 'Test Room (AI)'
        ], [
            'room_type' => 'group',
            'created_by' => 1,
            'is_active' => true
        ]);

        $this->info("📍 Using room: {$room->room_name} (ID: {$room->room_id})");

        // Add AI to room if not already
        $aiUser = $this->aiService->getAIUser();
        if (!$room->members->contains($aiUser->id)) {
            $room->members()->attach($aiUser->id, [
                'role' => 'bot',
                'joined_at' => now()
            ]);
            $this->info("➕ Added AI to room");
        }

        // Get test user
        $testUser = User::firstOrCreate([
            'email' => 'test@example.com'
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password')
        ]);

        // Get message from argument or use default
        $messageText = $this->argument('message') ?? 'AI, what is Laravel?';
        
        $this->info("💬 User message: {$messageText}");
        $this->newLine();

        // Create user message
        $message = ChatMessage::create([
            'room_id' => $room->room_id,
            'user_id' => $testUser->id,
            'message_text' => $messageText,
            'message_type' => 'text'
        ]);

        $this->info('⏳ Waiting for AI response...');
        
        // Generate AI response
        $response = $this->aiService->generateResponse($room, $message);

        if ($response) {
            $this->newLine();
            $this->info('🤖 AI Response:');
            $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->line($response);
            $this->line('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            
            // Save AI message
            ChatMessage::create([
                'room_id' => $room->room_id,
                'user_id' => $aiUser->id,
                'message_text' => $response,
                'message_type' => 'text'
            ]);

            $this->newLine();
            $this->info('✅ Test completed successfully!');
            return 0;
        } else {
            $this->error('❌ AI did not respond');
            $this->info('Check logs at storage/logs/laravel.log for errors');
            return 1;
        }
    }
}
