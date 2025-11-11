# 🤖 AI Chat Integration - MegaLearning

## 📚 Tổng Quan

Hệ thống chat realtime với AI tích hợp, cho phép:
- Chat 1-1 hoặc chat nhóm với AI
- AI hiểu ngữ cảnh cuộc trò chuyện
- Phản hồi tự nhiên, ngắn gọn, giống người thật
- Hoạt động realtime qua WebSockets/Broadcasting

---

## 🏗️ Kiến Trúc MVC

### **Models**
- `ChatRoom` - Phòng chat (group, private, subject)
- `ChatMessage` - Tin nhắn trong phòng
- `User` - Người dùng (bao gồm AI bot)

### **Controllers**
- `ChatController` - Web UI controller
- `ChatApiController` - RESTful API controller

### **Services**
- `AIService` - Tích hợp OpenAI API, xử lý logic AI

### **Events**
- `MessageSent` - Broadcasting realtime messages

---

## 📦 Cài Đặt

### 1. **Cấu hình Environment**

Copy `.env.example` sang `.env` và cấu hình:

```bash
# OpenAI Configuration
OPENAI_API_KEY=your-openai-api-key-here
OPENAI_API_URL=https://api.openai.com/v1/chat/completions
OPENAI_MODEL=gpt-3.5-turbo
AI_NAME="AI Assistant"
OPENAI_MAX_TOKENS=150
OPENAI_TEMPERATURE=0.7

# Broadcasting (for realtime)
BROADCAST_CONNECTION=pusher
# hoặc sử dụng Laravel Reverb, Soketi, etc.
```

### 2. **Cài đặt Dependencies**

```bash
composer install
npm install
```

### 3. **Chạy Migrations**

```bash
php artisan migrate
```

Migration sẽ tạo:
- `chat_rooms` table
- `chat_messages` table
- `chat_room_members` table
- `ai` role trong hệ thống

### 4. **Tạo AI User**

AI User sẽ được tự động tạo khi gửi tin nhắn đầu tiên (nếu có OPENAI_API_KEY).

Hoặc tạo thủ công:

```php
php artisan tinker

$aiUser = User::create([
    'name' => 'AI Assistant',
    'email' => 'ai@megalearning.local',
    'password' => bcrypt(Str::random(32))
]);

$aiUser->assignRole('ai');
```

---

## 🚀 Sử Dụng

### **Web Routes**

```php
// Xem danh sách phòng chat
GET /chat

// Vào phòng chat
GET /chat/{roomId}

// Tạo phòng mới (với AI)
POST /chat/create
{
    "room_name": "Laravel Study Group",
    "room_type": "group",
    "include_ai": true  // ← Thêm AI vào phòng
}

// Gửi tin nhắn
POST /chat/{roomId}/send
{
    "message_text": "Hey AI, explain MVC pattern?"
}
```

### **API Routes**

```php
// Lấy danh sách phòng
GET /api/chat/rooms

// Lấy tin nhắn trong phòng
GET /api/chat/rooms/{roomId}/messages

// Tạo phòng mới
POST /api/chat/rooms
{
    "room_name": "AI Study Group",
    "room_type": "group",
    "include_ai": true
}

// Gửi tin nhắn (AI sẽ tự động phản hồi)
POST /api/chat/rooms/{roomId}/messages
{
    "message_text": "AI, what is Laravel?"
}
```

---

## 🤖 Cách AI Hoạt Động

### **1. Trigger Điều Kiện**

AI sẽ phản hồi khi:
- Được mention trực tiếp (`AI`, `bot`, `assistant`)
- Message chứa câu hỏi (`?`, `what`, `how`, `why`...)
- Ngẫu nhiên 10% để giữ cuộc trò chuyện tự nhiên

### **2. Context Awareness**

- AI đọc 20 tin nhắn gần nhất để hiểu ngữ cảnh
- Biết ai đang nói (dựa vào `user.name`)
- Gọi tên người dùng khi trả lời

### **3. Response Style**

AI được config để:
- Trả lời ngắn gọn (2-3 câu)
- Giống người thật (có thể dùng emoji 😎)
- Thân thiện, hữu ích
- Không lặp lại lịch sử chat

### **4. System Prompt**

```
You are an AI chat participant named AI Assistant.
You are chatting in real-time with one or more human users.

Goals:
1. Respond naturally and quickly (short messages)
2. Adapt tone to context (fun, technical, supportive)
3. Understand who is speaking (name tags)
4. Only reply when addressed or relevant
5. Keep responses under 2-3 sentences
6. Use conversational tone (friendly, not robotic)
7. Never repeat chat history
8. Maintain awareness but summarize internally

Context: This is an E-Learning platform.
You can help with:
- Answering academic questions
- Explaining concepts
- Study tips
- Casual conversation
- Moderating discussions
```

---

## 🔧 Tùy Chỉnh AI

### **1. Thay đổi AI Model**

Trong `.env`:

```bash
# Sử dụng GPT-4
OPENAI_MODEL=gpt-4

# Hoặc local model (Ollama, vLLM)
OPENAI_API_URL=http://localhost:11434/v1/chat/completions
OPENAI_MODEL=llama3
```

### **2. Điều chỉnh Personality**

Sửa `AIService::buildSystemPrompt()`:

```php
return <<<PROMPT
You are a funny, witty AI tutor named {$this->aiName}.
You love using emojis and dad jokes.
Help students learn while keeping it fun! 🎓🤣
PROMPT;
```

### **3. Tăng độ ngẫu nhiên**

Trong `AIService::shouldRespond()`:

```php
// Tăng từ 10% → 30%
if (rand(1, 100) <= 30) {
    return true;
}
```

---

## 📊 Database Schema

```sql
chat_rooms
├── room_id (PK)
├── room_name
├── room_type (group/private/subject)
├── subject_id (FK, nullable)
├── created_by (FK)
└── is_active

chat_messages
├── message_id (PK)
├── room_id (FK)
├── user_id (FK)  ← AI user cũng là user
├── message_text
├── message_type
└── is_deleted

chat_room_members
├── room_id (FK)
├── user_id (FK)
├── role (admin/member/bot)  ← AI có role 'bot'
└── joined_at
```

---

## 🎯 Use Cases

### **1. AI Study Buddy**

```
Student: "AI, explain the difference between Eloquent and Query Builder"
AI: "Great question! Eloquent is Laravel's ORM with model-based syntax,
     while Query Builder is lower-level SQL. Think of Eloquent as 
     'speaking Laravel', Query Builder as 'speaking SQL' 😊"
```

### **2. Group Discussion with AI**

```
Alice: "Anyone know how to optimize database queries?"
Bob: "Use eager loading?"
AI: "Exactly @Bob! Use with() to prevent N+1 queries. 
     For example: User::with('posts')->get(). Simple but powerful! ⚡"
```

### **3. AI-Moderated Q&A Forum**

```
Student: "What's the best Laravel version?"
AI: "Latest stable is Laravel 11! It includes:
     - Improved performance
     - Better Eloquent features
     - Streamlined structure
     Check the docs for migration guide! 📚"
```

---

## 🐛 Troubleshooting

### **AI không phản hồi?**

1. Kiểm tra `OPENAI_API_KEY` đã đúng chưa
2. Xem logs: `storage/logs/laravel.log`
3. Test API key:

```bash
curl https://api.openai.com/v1/models \
  -H "Authorization: Bearer YOUR_API_KEY"
```

### **Phản hồi chậm?**

- Giảm `OPENAI_MAX_TOKENS` (150 → 100)
- Sử dụng `gpt-3.5-turbo` thay vì `gpt-4`
- Enable queue: `QUEUE_CONNECTION=redis`

### **AI phản hồi quá nhiều?**

- Giảm xác suất random trong `shouldRespond()` (10% → 5%)
- Thêm điều kiện kiểm tra ngặt hơn

---

## 📝 API Examples

### **Tạo phòng chat với AI**

```bash
curl -X POST http://localhost/api/chat/rooms \
  -H "Content-Type: application/json" \
  -d '{
    "room_name": "Laravel Masterclass",
    "room_type": "group",
    "include_ai": true
  }'
```

### **Gửi tin nhắn**

```bash
curl -X POST http://localhost/api/chat/rooms/1/messages \
  -H "Content-Type: application/json" \
  -d '{
    "message_text": "AI, what is dependency injection?"
  }'
```

---

## 🎓 Best Practices

1. **Luôn add AI vào group study rooms**
2. **Đặt tên AI rõ ràng** (AI Assistant, Study Bot, etc.)
3. **Giới hạn context** (20 messages) để tránh token limit
4. **Cache AI user ID** để giảm DB queries
5. **Log AI responses** để phân tích và cải thiện

---

## 🚀 Next Steps

- [ ] Thêm typing indicator khi AI đang "suy nghĩ"
- [ ] Voice message support
- [ ] AI có thể gửi ảnh/diagram
- [ ] Multi-language support
- [ ] Fine-tune AI model cho academic context

---

## 📞 Support

Nếu gặp vấn đề, check:
- Laravel logs: `storage/logs/laravel.log`
- OpenAI status: https://status.openai.com
- Broadcasting config: `config/broadcasting.php`

---

**Happy Chatting! 🎉🤖**
