# 🚀 Quick Start - AI Chat System

## ⚡ Cài Đặt Nhanh (5 phút)

### **1. Setup Environment**

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env
DB_CONNECTION=mysql
DB_DATABASE=megalearning
DB_USERNAME=root
DB_PASSWORD=

# Add OpenAI API Key (get from https://platform.openai.com/api-keys)
OPENAI_API_KEY=sk-your-api-key-here
AI_NAME="Study Bot"
```

### **2. Install Dependencies**

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### **3. Setup Database**

```bash
# Run migrations
php artisan migrate

# Seed roles and sample data
php artisan db:seed
```

### **4. Build Frontend**

```bash
# Development
npm run dev

# Or production
npm run build
```

### **5. Start Server**

```bash
# Start Laravel
php artisan serve

# In another terminal, start queue worker
php artisan queue:work
```

---

## 🧪 Test AI Chat

### **Option 1: Command Line**

```bash
# Basic test
php artisan chat:test-ai

# Custom message
php artisan chat:test-ai "Explain MVC pattern"

# Output:
# ✅ OpenAI is configured
# 🤖 AI Name: Study Bot
# 💬 User message: Explain MVC pattern
# 🤖 AI Response:
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
# MVC stands for Model-View-Controller! It separates 
# your app into data (Model), UI (View), and logic 
# (Controller). Clean and organized! 🎯
# ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

### **Option 2: API**

```bash
# Create room with AI
curl -X POST http://localhost:8000/api/chat/rooms \
  -H "Content-Type: application/json" \
  -d '{
    "room_name": "Study Group",
    "room_type": "group",
    "include_ai": true
  }'

# Send message (AI will auto-respond)
curl -X POST http://localhost:8000/api/chat/rooms/1/messages \
  -H "Content-Type: application/json" \
  -d '{
    "message_text": "AI, help me understand Laravel routing?"
  }'
```

### **Option 3: Web UI**

1. Đăng nhập: `http://localhost:8000/login`
2. Vào Chat: `http://localhost:8000/chat`
3. Tạo room mới, tick ✅ "Include AI"
4. Gửi message → AI sẽ tự động reply!

---

## 📁 Project Structure

```
app/
├── Models/
│   ├── ChatRoom.php          # Phòng chat
│   ├── ChatMessage.php       # Tin nhắn
│   └── User.php              # User (bao gồm AI bot)
│
├── Services/
│   └── AIService.php         # ⭐ AI logic & OpenAI integration
│
├── Http/Controllers/
│   ├── ChatController.php    # Web controller
│   └── Api/
│       └── ChatApiController.php  # API controller
│
├── Events/
│   └── MessageSent.php       # Broadcasting event
│
└── Console/Commands/
    └── TestAIChat.php        # Test command

routes/
├── web.php                   # Web routes (/chat/...)
├── api.php                   # API routes (/api/chat/...)
└── channels.php              # Broadcasting channels

database/migrations/
├── *_create_chat_rooms_table.php
├── *_create_chat_messages_table.php
├── *_create_chat_room_members_table.php
└── *_add_ai_role.php

docs/
├── AI_CHAT_GUIDE.md         # 📖 Full documentation
└── BROADCASTING_SETUP.md    # 🔴 Realtime setup guide
```

---

## 🎯 Core Features

### ✅ Implemented

- [x] MVC Architecture cho Chat System
- [x] Chat Rooms (group, private, subject-based)
- [x] AI Integration với OpenAI API
- [x] Context-aware AI responses
- [x] AI auto-trigger (mentions, questions, random)
- [x] RESTful API
- [x] Broadcasting events (MessageSent)
- [x] Multi-user support
- [x] AI role management
- [x] Testing suite

### 📝 Architecture Highlights

**Models (Data Layer)**
```php
ChatRoom (1) ──→ (N) ChatMessage
ChatRoom (N) ←→ (N) User (through chat_room_members)
User (có thể là AI bot với role='ai')
```

**Services (Business Logic)**
```php
AIService
├── generateResponse()      // Tạo AI response
├── shouldRespond()         // Quyết định có reply không
├── buildConversationContext()  // Context từ 20 messages gần nhất
└── buildSystemPrompt()     // Personality & instructions
```

**Controllers (Request Handling)**
```php
ChatController (Web)
├── index()         // List rooms
├── show($id)       // Chat UI
├── sendMessage()   // Send + trigger AI
└── store()         // Create room

ChatApiController (API)
├── getRooms()      // GET /api/chat/rooms
├── sendMessage()   // POST /api/chat/rooms/{id}/messages
└── createRoom()    // POST /api/chat/rooms
```

**Events (Broadcasting)**
```php
MessageSent implements ShouldBroadcast
├── broadcastOn(): PrivateChannel('chat-room.{id}')
└── broadcastAs(): 'message.sent'
```

---

## 🤖 AI Behavior

### **Triggers (Khi nào AI reply?)**

1. **Mentioned**: `"AI, can you help?"` → ✅ Always respond
2. **Questions**: `"What is Laravel?"` → ✅ Always respond
3. **Random**: 10% chance → ✅ Keep conversation natural

### **Response Style**

- Short (2-3 sentences)
- Conversational & friendly
- Uses emojis when appropriate
- Addresses users by name
- Context-aware (reads last 20 messages)

### **System Prompt (Customizable)**

```
You are an AI chat participant named Study Bot.
Goals:
1. Respond naturally and quickly
2. Adapt tone to context
3. Only reply when relevant
4. Keep responses under 2-3 sentences
5. Help with academic questions

Context: E-Learning platform
```

---

## 🔧 Configuration

### **Environment Variables**

```bash
# OpenAI
OPENAI_API_KEY=sk-...          # Required
OPENAI_MODEL=gpt-3.5-turbo     # or gpt-4
AI_NAME="Study Bot"            # Display name
OPENAI_MAX_TOKENS=150          # Response length
OPENAI_TEMPERATURE=0.7         # Creativity (0-1)

# Broadcasting (for realtime)
BROADCAST_CONNECTION=pusher    # or reverb, redis
PUSHER_APP_KEY=...
PUSHER_APP_SECRET=...
PUSHER_APP_CLUSTER=ap1

# Queue (recommended for production)
QUEUE_CONNECTION=redis         # or database
```

### **Config Files**

- `config/services.php` → OpenAI settings
- `config/broadcasting.php` → Realtime config
- `routes/channels.php` → Broadcasting auth

---

## 📊 API Endpoints

### **Chat Rooms**

```http
GET    /api/chat/rooms                    # List all rooms
POST   /api/chat/rooms                    # Create room
GET    /api/chat/rooms/{id}/messages      # Get messages
POST   /api/chat/rooms/{id}/messages      # Send message (AI auto-respond)
```

### **Example Requests**

```javascript
// Create room with AI
POST /api/chat/rooms
{
  "room_name": "Laravel Study Group",
  "room_type": "group",
  "include_ai": true  // ← Add AI to room
}

// Send message
POST /api/chat/rooms/1/messages
{
  "message_text": "AI, explain dependency injection"
}

// Response includes AI's reply (after ~2-3 seconds)
```

---

## 🐛 Troubleshooting

### **AI không phản hồi?**

```bash
# 1. Check API key
php artisan tinker
>>> config('services.openai.api_key')

# 2. Test manually
php artisan chat:test-ai "Hello AI"

# 3. Check logs
tail -f storage/logs/laravel.log
```

### **Messages không realtime?**

```bash
# 1. Check broadcasting config
php artisan config:clear

# 2. Start queue worker
php artisan queue:work

# 3. Verify Pusher/Reverb running
# See docs/BROADCASTING_SETUP.md
```

---

## 📚 Documentation

- **Full Guide**: [docs/AI_CHAT_GUIDE.md](./AI_CHAT_GUIDE.md)
- **Broadcasting Setup**: [docs/BROADCASTING_SETUP.md](./BROADCASTING_SETUP.md)
- **API Reference**: (coming soon)

---

## 🎓 Example Conversations

### **Academic Help**

```
Student: "AI, what's the difference between GET and POST?"
AI: "GET retrieves data (visible in URL), POST sends data 
     (hidden in body). Use GET for reading, POST for writing! 📝"
```

### **Group Study**

```
Alice: "Anyone understand Eloquent relationships?"
Bob: "I'm confused too"
AI: "Happy to help! Eloquent relationships connect models. 
     belongsTo (1→1), hasMany (1→N), belongsToMany (N→N). 
     Think foreign keys with magic! ✨"
```

### **Code Help**

```
Student: "AI, how do I create a controller?"
AI: "Easy! Run: php artisan make:controller YourController
     Then add methods for your routes. MVC in action! 🚀"
```

---

## 🚀 Next Steps

1. **Run Migrations**: `php artisan migrate`
2. **Test AI**: `php artisan chat:test-ai`
3. **Setup Broadcasting**: See [BROADCASTING_SETUP.md](./BROADCASTING_SETUP.md)
4. **Customize AI**: Edit `AIService::buildSystemPrompt()`
5. **Deploy**: Queue workers + supervisor

---

## 💡 Tips

- Set `OPENAI_MAX_TOKENS=100` cho responses nhanh hơn
- Use `gpt-3.5-turbo` thay vì `gpt-4` để save cost
- Enable queue (`QUEUE_CONNECTION=redis`) cho performance
- Cache AI user ID để giảm DB queries
- Monitor OpenAI usage tại dashboard

---

**Happy Coding! 🎉🤖**

Built with ❤️ using Laravel 11 + OpenAI
