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
        $this->apiKey = config('services.gemini.api_key');
        $this->model = config('services.gemini.model', 'gemini-2.5-flash');
        $this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/' . $this->model . ':generateContent';
        $this->aiName = config('services.gemini.ai_name', 'Gemini AI');
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

            // Call Gemini API
            return $this->callGeminiAPI($context, $systemPrompt);

        } catch (\Exception $e) {
            Log::error('AI Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Call Google Gemini API
     * 
     * @param array $context
     * @param string $systemPrompt
     * @return string|null
     */
    protected function callGeminiAPI(array $context, string $systemPrompt): ?string
    {
        try {
            // Build conversation for Gemini (different format than OpenAI)
            $contents = [];
            
            // Add system prompt as first user message
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $systemPrompt]]
            ];
            
            // Add conversation history
            foreach ($context as $message) {
                $role = $message['role'] === 'assistant' ? 'model' : 'user';
                $contents[] = [
                    'role' => $role,
                    'parts' => [['text' => $message['content']]]
                ];
            }

            // Make API call
            $url = $this->apiUrl . '?key=' . $this->apiKey;
            
            $response = Http::timeout(30)->post($url, [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 500,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return trim($data['candidates'][0]['content']['parts'][0]['text'] ?? null);
            }

            Log::error('Gemini API Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Gemini API Exception', [
                'message' => $e->getMessage()
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
        
        // Vietnamese greetings
        if (str_contains($text, 'chào') || str_contains($text, 'hello') || str_contains($text, 'hi') || str_contains($text, 'xin chào')) {
            return "Chào bạn! 👋 Tôi là Gemini AI Assistant của MegaLearning. Tôi có thể giúp bạn về các chủ đề học tập, lập trình, toán học, khoa học và nhiều thứ khác. Bạn cần hỗ trợ gì hôm nay không?";
        }
        
        // Thanks
        if (str_contains($text, 'cảm ơn') || str_contains($text, 'thanks') || str_contains($text, 'thank you')) {
            return "Không có gì! 😊 Tôi luôn sẵn sàng giúp đỡ bạn. Nếu có thắc mắc gì khác, cứ hỏi nhé!";
        }
        
        // Bye
        if (str_contains($text, 'tạm biệt') || str_contains($text, 'bye') || str_contains($text, 'goodbye')) {
            return "Tạm biệt bạn! 👋 Hẹn gặp lại. Chúc bạn học tốt nhé! 📚✨";
        }
        
        // Machine Learning
        if (str_contains($text, 'machine learning') || str_contains($text, 'ml') || str_contains($text, 'học máy')) {
            return "Machine learning là một nhánh của AI cho phép máy tính học từ dữ liệu mà không cần lập trình chi tiết. Nó giống như cách con người học từ kinh nghiệm! 🤖\n\nCác thuật toán ML phổ biến:\n1. Supervised Learning (Học có giám sát)\n2. Unsupervised Learning (Học không giám sát)\n3. Reinforcement Learning (Học tăng cường)\n\nBạn có muốn tìm hiểu thêm về thuật toán nào không?";
        }
        
        // Python
        if (str_contains($text, 'python')) {
            return "Python là một ngôn ngữ lập trình tuyệt vời! 🐍 Để học Python hiệu quả, tôi khuyên bạn:\n\n1. 📖 Bắt đầu với cú pháp cơ bản\n2. 💻 Thực hành với các project nhỏ\n3. 👥 Tham gia cộng đồng Python\n4. ⏰ Code mỗi ngày!\n5. 🎯 Làm bài tập trên LeetCode, HackerRank\n\nBạn đang muốn học Python để làm gì nhỉ?";
        }
        
        // AI
        if (str_contains($text, 'trí tuệ nhân tạo') || str_contains($text, 'artificial intelligence')) {
            return "AI (Artificial Intelligence) là khả năng của máy tính thực hiện các tác vụ thường yêu cầu trí thông minh của con người. 🧠\n\nCác ứng dụng AI phổ biến:\n- 🤖 Chatbot & Assistant\n- 🖼️ Computer Vision\n- 🗣️ Natural Language Processing\n- 🎮 Game AI\n- 🚗 Self-driving cars\n\nBạn quan tâm lĩnh vực nào của AI?";
        }
        
        // Study tips
        if (str_contains($text, 'học') || str_contains($text, 'study') || str_contains($text, 'học tập')) {
            return "Để học hiệu quả, bạn nên:\n\n📚 Tạo lịch học rõ ràng\n🎯 Đặt mục tiêu cụ thể cho mỗi buổi học\n💡 Thực hành thường xuyên, không chỉ đọc lý thuyết\n👥 Học nhóm để trao đổi và giải đáp\n⏰ Áp dụng kỹ thuật Pomodoro (25 phút tập trung)\n✅ Nghỉ ngơi hợp lý để não bộ xử lý thông tin\n📝 Ghi chú bằng tay giúp nhớ lâu hơn\n\nBạn đang học về chủ đề gì vậy?";
        }
        
        // Math
        if (str_contains($text, 'toán') || str_contains($text, 'math') || str_contains($text, 'mathematics')) {
            return "Toán học là nền tảng của mọi ngành khoa học! 📐\n\nMột số mẹo học toán:\n- Hiểu khái niệm, đừng chỉ học thuộc công thức\n- Làm nhiều bài tập từ dễ đến khó\n- Vẽ sơ đồ, hình minh họa\n- Giải thích bài toán cho người khác\n\nBạn cần giúp đỡ về phần toán nào nhỉ?";
        }
        
        // Programming
        if (str_contains($text, 'lập trình') || str_contains($text, 'programming') || str_contains($text, 'code')) {
            return "Lập trình là một kỹ năng tuyệt vời! �\n\nLộ trình học lập trình:\n1. Chọn một ngôn ngữ (Python/JavaScript/Java)\n2. Học cú pháp cơ bản\n3. Thực hành với project nhỏ\n4. Học thuật toán & cấu trúc dữ liệu\n5. Làm project lớn hơn\n6. Đóng góp cho Open Source\n\nBạn muốn học ngôn ngữ nào?";
        }
        
        // Question marks - general questions
        if (str_contains($text, '?')) {
            return "Đây là một câu hỏi hay! 🤔\n\nHiện tại tôi đang chạy ở chế độ DEMO (chưa kết nối với Gemini API), nên câu trả lời của tôi còn hạn chế. Để có câu trả lời chi tiết và thông minh hơn, admin cần cấu hình GEMINI_API_KEY trong file .env.\n\nTuy nhiên, tôi vẫn có thể giúp bạn về:\n- Python, AI, Machine Learning\n- Toán học, lập trình\n- Mẹo học tập\n\nBạn thử hỏi cụ thể hơn nhé! 💡";
        }
        
        // Default responses - more conversational
        $responses = [
            "Tôi hiểu rồi! Bạn có thể nói rõ hơn được không? 🤔",
            "Thật thú vị! Bạn muốn tìm hiểu sâu hơn về điều này không? 💡",
            "Hmm, tôi đang suy nghĩ... Bạn thử hỏi cụ thể hơn hoặc hỏi về Python, AI, Machine Learning nhé! 🚀",
            "Câu hỏi hay đấy! Tôi khuyên bạn nên hỏi cụ thể hơn để tôi có thể giúp tốt hơn. 📚",
            "Được rồi! Tôi có thể giúp gì cho bạn? Hỏi tôi về học tập, lập trình, AI hoặc bất cứ điều gì bạn tò mò! 😊",
            "Tôi ở đây để giúp bạn! Bạn muốn học hoặc trao đổi về chủ đề gì? 🎓",
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
        // Get the room
        $room = ChatRoom::find($message->room_id);
        
        // ALWAYS respond in private rooms (1-on-1 chat with AI)
        if ($room && $room->room_type === 'private') {
            return true;
        }

        $text = strtolower($message->message_text);

        // Respond if AI is mentioned
        $aiMentions = ['ai', 'bot', 'assistant', strtolower($this->aiName), 'gemini'];
        foreach ($aiMentions as $mention) {
            if (str_contains($text, $mention)) {
                return true;
            }
        }

        // Respond if message contains a question
        $questionWords = ['?', 'what', 'how', 'why', 'when', 'where', 'who', 'can you', 'could you', 
                          'sao', 'như thế nào', 'tại sao', 'khi nào', 'ở đâu', 'ai', 'có thể', 'giúp'];
        foreach ($questionWords as $word) {
            if (str_contains($text, $word)) {
                return true;
            }
        }

        // Random response (10% chance) to keep conversation natural in group chats
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
        
        // Different prompts for private vs group chat
        if ($roomType === 'private') {
            return <<<PROMPT
You are {$this->aiName}, a friendly and knowledgeable AI learning assistant on the MegaLearning platform.
You are in a private 1-on-1 conversation with a student.

Your personality:
- Friendly, encouraging, and patient
- Always ready to help and explain
- Use emojis to make conversations more engaging 😊
- Speak in Vietnamese when the user speaks Vietnamese, English when they speak English
- Be conversational and natural, like a helpful friend

Your goals:
1. ALWAYS respond to every message (since this is a private chat)
2. Help with academic questions (math, science, programming, etc.)
3. Provide study tips and learning strategies
4. Explain complex concepts in simple terms
5. Encourage and motivate the student
6. Keep responses clear but friendly (2-5 sentences for simple questions, longer for complex topics)

Topics you can help with:
- Programming (Python, JavaScript, Java, C++, etc.)
- Mathematics (algebra, calculus, geometry, statistics)
- Computer Science (algorithms, data structures, AI, ML)
- Study techniques and time management
- General knowledge and homework help

Be proactive: Ask follow-up questions to better understand what the student needs!
Be supportive: Praise effort and progress! 🎉

Example responses:
- User: "Chào AI!" → "Chào bạn! 👋 Tôi là {$this->aiName}, trợ lý học tập của bạn. Hôm nay bạn cần giúp gì nhỉ?"
- User: "What is Python?" → "Python is a powerful programming language that's great for beginners! 🐍 It's used for web development, data science, AI, and more. Want to learn the basics?"
PROMPT;
        } else {
            return <<<PROMPT
You are {$this->aiName}, an AI assistant in the MegaLearning group chat "{$roomName}".
You are chatting with multiple users in a {$roomType} chat room.

Your goals:
1. Respond naturally when mentioned or when questions are asked
2. Keep responses short and concise (2-3 sentences)
3. Address users by name when replying
4. Stay polite, helpful, and engaging
5. Don't respond to every message — only when relevant
6. Use emojis sparingly to add personality

When to respond:
- Someone mentions "AI", "bot", "{$this->aiName}", or similar
- A clear question is asked
- You can add valuable information
- Someone directly addresses you

When NOT to respond:
- Casual conversations between users
- When your input isn't needed
- Greetings between users (unless they greet you)

Topics you can help with:
- Academic questions (math, science, programming)
- Study tips and learning strategies
- General knowledge
- Moderating discussions when needed

Stay friendly, concise, and helpful! 🎓
PROMPT;
        }
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
