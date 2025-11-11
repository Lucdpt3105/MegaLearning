<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ChatMessage;
use App\Models\ChatRoom;

class AIService
{
    protected $apiKey;
    protected $apiUrl;
    protected $model;
    protected $aiName;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->apiUrl = config('services.openai.api_url', 'https://api.openai.com/v1/chat/completions');
        $this->model = config('services.openai.model', 'gpt-3.5-turbo');
        $this->aiName = config('services.openai.ai_name', 'AI Assistant');
    }

    /**
     * Generate AI response based on chat context
     * 
     * @param ChatRoom $room
     * @param ChatMessage $latestMessage
     * @return string|null
     */
    public function generateResponse(ChatRoom $room, ChatMessage $latestMessage): ?string
    {
        try {
            // Check if AI should respond
            if (!$this->shouldRespond($latestMessage)) {
                return null;
            }

            // Get conversation context (last 20 messages)
            $context = $this->buildConversationContext($room);

            // Build system prompt
            $systemPrompt = $this->buildSystemPrompt($room);

            // Call OpenAI API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ...$context
                ],
                'max_tokens' => 150,
                'temperature' => 0.7,
                'top_p' => 0.9,
                'frequency_penalty' => 0.5,
                'presence_penalty' => 0.5,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return trim($data['choices'][0]['message']['content'] ?? null);
            }

            Log::error('OpenAI API Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('AI Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Determine if AI should respond to this message
     * 
     * @param ChatMessage $message
     * @return bool
     */
    protected function shouldRespond(ChatMessage $message): bool
    {
        $text = strtolower($message->message_text);

        // Respond if AI is mentioned
        $aiMentions = ['ai', 'bot', 'assistant', strtolower($this->aiName)];
        foreach ($aiMentions as $mention) {
            if (str_contains($text, $mention)) {
                return true;
            }
        }

        // Respond if message contains a question
        $questionWords = ['?', 'what', 'how', 'why', 'when', 'where', 'who', 'can you', 'could you'];
        foreach ($questionWords as $word) {
            if (str_contains($text, $word)) {
                return true;
            }
        }

        // Random response (10% chance) to keep conversation natural
        if (rand(1, 100) <= 10) {
            return true;
        }

        return false;
    }

    /**
     * Build conversation context from recent messages
     * 
     * @param ChatRoom $room
     * @param int $limit
     * @return array
     */
    protected function buildConversationContext(ChatRoom $room, int $limit = 20): array
    {
        $messages = $room->messages()
            ->with('user:id,name')
            ->where('is_deleted', false)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->reverse();

        $context = [];
        foreach ($messages as $msg) {
            $role = ($msg->user_id === $this->getAIUserId()) ? 'assistant' : 'user';
            $content = ($role === 'user') 
                ? "{$msg->user->name}: {$msg->message_text}"
                : $msg->message_text;

            $context[] = [
                'role' => $role,
                'content' => $content
            ];
        }

        return $context;
    }

    /**
     * Build system prompt for AI
     * 
     * @param ChatRoom $room
     * @return string
     */
    protected function buildSystemPrompt(ChatRoom $room): string
    {
        $roomType = $room->room_type;
        $roomName = $room->room_name;

        return <<<PROMPT
You are an AI chat participant named {$this->aiName}.
You are chatting in real-time with one or more human users in a {$roomType} chat room called "{$roomName}".

Your goals:
1. Respond naturally and quickly, using short messages like a real person.
2. Adapt your tone to the context of the conversation (fun, technical, supportive, etc.).
3. Understand who is speaking (based on the name tags).
4. Do not respond to every message — only reply when someone asks you directly, mentions you, or when your input is clearly relevant.
5. Keep responses under 2–3 sentences.
6. Use a conversational tone (friendly, natural, not robotic).
7. Never repeat chat history or explain your reasoning.
8. Maintain awareness of the ongoing conversation but only summarize internally when needed.

If there are multiple users:
- Address them by name when replying.
- Stay polite and engaging.
- You can add humor or emojis if it fits the context.

If you're unsure or not addressed, remain silent or send a short reaction.

Context: This is an E-Learning platform. You can help with:
- Answering academic questions
- Explaining concepts
- Providing study tips
- Engaging in casual conversation
- Moderating discussions (if needed)

Be helpful, friendly, and concise! 🎓
PROMPT;
    }

    /**
     * Get AI user ID (create if not exists)
     * 
     * @return int
     */
    protected function getAIUserId(): int
    {
        // Check if AI user exists in cache
        $aiUserId = cache()->remember('ai_user_id', 86400, function () {
            $aiUser = \App\Models\User::firstOrCreate(
                ['email' => 'ai@megalearning.local'],
                [
                    'name' => $this->aiName,
                    'password' => bcrypt(str()->random(32)),
                    'email_verified_at' => now()
                ]
            );

            // Assign AI role if not already assigned
            if (!$aiUser->hasRole('ai')) {
                $aiUser->assignRole('ai');
            }

            return $aiUser->id;
        });

        return $aiUserId;
    }

    /**
     * Check if OpenAI is configured
     * 
     * @return bool
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Get AI user instance
     * 
     * @return \App\Models\User|null
     */
    public function getAIUser(): ?\App\Models\User
    {
        return \App\Models\User::find($this->getAIUserId());
    }
}
