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

            // DEMO MODE: If no API key, use mock responses
            if (empty($this->apiKey)) {
                return $this->generateMockResponse($latestMessage);
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
     * Generate mock response for demo mode (when no API key)
     * 
     * @param ChatMessage $message
     * @return string
     */
    protected function generateMockResponse(ChatMessage $message): string
    {
        $text = strtolower($message->message_text);
        
        // Detect topics and respond accordingly
        if (str_contains($text, 'machine learning') || str_contains($text, 'ml')) {
            return "Machine learning là một nhánh của AI cho phép máy tính học từ dữ liệu mà không cần lập trình chi tiết. Nó giống như cách con người học từ kinh nghiệm! 🤖 Bạn có muốn tìm hiểu thêm về các thuật toán ML không?";
        }
        
        if (str_contains($text, 'python')) {
            return "Python là một ngôn ngữ lập trình tuyệt vời! 🐍 Để học Python hiệu quả, tôi khuyên bạn:\n1. Bắt đầu với cú pháp cơ bản\n2. Thực hành với các project nhỏ\n3. Tham gia cộng đồng Python\n4. Code mỗi ngày! 💪";
        }
        
        if (str_contains($text, 'ai') || str_contains($text, 'trí tuệ nhân tạo')) {
            return "AI (Artificial Intelligence) là khả năng của máy tính thực hiện các tác vụ thường yêu cầu trí thông minh của con người. Hiện nay AI đang được ứng dụng rộng rãi trong nhiều lĩnh vực! ✨ Bạn quan tâm lĩnh vực nào của AI?";
        }
        
        if (str_contains($text, 'học') || str_contains($text, 'study')) {
            return "Để học hiệu quả, bạn nên:\n📚 Tạo lịch học rõ ràng\n🎯 Đặt mục tiêu cụ thể\n💡 Thực hành thường xuyên\n👥 Học nhóm để trao đổi\n✅ Nghỉ ngơi hợp lý\nBạn đang học về chủ đề gì vậy?";
        }
        
        if (str_contains($text, 'hello') || str_contains($text, 'hi') || str_contains($text, 'chào')) {
            return "Chào bạn! 👋 Tôi là AI Assistant của MegaLearning. Tôi có thể giúp bạn về các chủ đề học tập, lập trình, và nhiều thứ khác. Bạn cần hỗ trợ gì không?";
        }
        
        if (str_contains($text, 'cảm ơn') || str_contains($text, 'thanks')) {
            return "Không có gì! 😊 Tôi luôn sẵn sàng giúp đỡ bạn. Nếu có thắc mắc gì khác, cứ hỏi nhé!";
        }
        
        if (str_contains($text, '?')) {
            return "Đây là một câu hỏi hay! 🤔 Hiện tại tôi đang chạy ở chế độ DEMO (chưa có OpenAI API key), nên câu trả lời của tôi khá đơn giản. Để có câu trả lời chi tiết và thông minh hơn, admin cần cấu hình OPENAI_API_KEY trong file .env nhé! 💡";
        }
        
        // Default responses
        $responses = [
            "Tôi hiểu rồi! Bạn có thể nói rõ hơn được không? 🤔",
            "Thật thú vị! Bạn muốn tìm hiểu sâu hơn về điều này không? 💡",
            "Được thôi! Tôi đang ở chế độ DEMO nên câu trả lời hơi đơn giản. Bạn thử hỏi về Python, AI, hoặc Machine Learning xem! 🚀",
            "Hmm, câu hỏi hay đấy! Tôi khuyên bạn nên tìm hiểu thêm trên Google hoặc ChatGPT để có câu trả lời chi tiết hơn nhé! 📚",
        ];
        
        return $responses[array_rand($responses)];
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
