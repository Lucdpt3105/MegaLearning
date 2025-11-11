# ✅ AI Chat System - Setup Checklist

## 📋 Bước 1: Environment Setup

- [ ] Copy `.env.example` → `.env`
  ```bash
  cp .env.example .env
  ```

- [ ] Generate application key
  ```bash
  php artisan key:generate
  ```

- [ ] Configure database
  ```env
  DB_CONNECTION=mysql
  DB_DATABASE=megalearning
  DB_USERNAME=root
  DB_PASSWORD=your_password
  ```

- [ ] Add OpenAI API Key (Get from: https://platform.openai.com/api-keys)
  ```env
  OPENAI_API_KEY=sk-your-key-here
  OPENAI_MODEL=gpt-3.5-turbo
  AI_NAME="Study Bot"
  ```

---

## 📋 Bước 2: Install Dependencies

- [ ] Install PHP dependencies
  ```bash
  composer install
  ```

- [ ] Install JavaScript dependencies
  ```bash
  npm install
  ```

---

## 📋 Bước 3: Database Setup

- [ ] Run migrations
  ```bash
  php artisan migrate
  ```

- [ ] Seed roles and data (optional)
  ```bash
  php artisan db:seed
  ```

- [ ] Verify 'ai' role exists
  ```bash
  php artisan tinker
  >>> \Spatie\Permission\Models\Role::where('name', 'ai')->exists()
  ```

---

## 📋 Bước 4: Test AI Integration

- [ ] Test AI configuration
  ```bash
  php artisan chat:test-ai
  ```
  
  ✅ Expected output:
  ```
  ✅ OpenAI is configured
  🤖 AI Name: Study Bot
  📦 Model: gpt-3.5-turbo
  💬 User message: AI, what is Laravel?
  🤖 AI Response:
  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  [AI's response here]
  ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
  ✅ Test completed successfully!
  ```

- [ ] Test with custom message
  ```bash
  php artisan chat:test-ai "Explain MVC pattern"
  ```

---

## 📋 Bước 5: Start Development Server

- [ ] Start Laravel server
  ```bash
  php artisan serve
  ```
  → Server running at: http://localhost:8000

- [ ] Start frontend build (in another terminal)
  ```bash
  npm run dev
  ```

- [ ] Start queue worker (in another terminal) - **Important for AI responses!**
  ```bash
  php artisan queue:work
  ```

---

## 📋 Bước 6: Test Web UI

- [ ] Register/Login tại http://localhost:8000/login

- [ ] Navigate to chat: http://localhost:8000/chat

- [ ] Create a new room
  - Room name: "Test Room"
  - Room type: Group
  - ✅ Check "Include AI"

- [ ] Send a message
  - Type: "AI, hello!"
  - Press Send
  - Wait 2-3 seconds
  - ✅ AI should respond

---

## 📋 Bước 7: Test API

- [ ] Test create room endpoint
  ```bash
  curl -X POST http://localhost:8000/api/chat/rooms \
    -H "Content-Type: application/json" \
    -d '{
      "room_name": "API Test Room",
      "room_type": "group",
      "include_ai": true
    }'
  ```

- [ ] Test send message endpoint (use room_id from above)
  ```bash
  curl -X POST http://localhost:8000/api/chat/rooms/1/messages \
    -H "Content-Type: application/json" \
    -d '{
      "message_text": "AI, what is Laravel?"
    }'
  ```

- [ ] Verify AI response in database
  ```bash
  php artisan tinker
  >>> \App\Models\ChatMessage::where('room_id', 1)->latest()->first()
  ```

---

## 📋 Bước 8: Setup Broadcasting (Optional - for Realtime)

- [ ] Choose broadcasting driver:
  - [ ] **Option A: Pusher** (Easiest, Free tier available)
  - [ ] **Option B: Laravel Reverb** (Laravel 11 built-in)
  - [ ] **Option C: Soketi** (Self-hosted)

- [ ] Follow setup guide: `docs/BROADCASTING_SETUP.md`

- [ ] Update `.env` with broadcasting credentials
  ```env
  BROADCAST_CONNECTION=pusher
  PUSHER_APP_KEY=...
  PUSHER_APP_SECRET=...
  ```

- [ ] Install frontend dependencies
  ```bash
  npm install --save-dev pusher-js laravel-echo
  ```

- [ ] Configure `resources/js/bootstrap.js`

- [ ] Test broadcasting
  ```bash
  php artisan tinker
  >>> $message = \App\Models\ChatMessage::first();
  >>> broadcast(new \App\Events\MessageSent($message));
  ```

---

## 📋 Bước 9: Customization (Optional)

- [ ] Customize AI personality
  - Edit: `app/Services/AIService.php`
  - Method: `buildSystemPrompt()`

- [ ] Adjust AI trigger logic
  - Edit: `app/Services/AIService.php`
  - Method: `shouldRespond()`

- [ ] Change AI name
  - Update `.env`: `AI_NAME="Your Bot Name"`

- [ ] Adjust response length
  - Update `.env`: `OPENAI_MAX_TOKENS=200`

- [ ] Change AI model
  - Update `.env`: `OPENAI_MODEL=gpt-4` (costs more)

---

## 📋 Bước 10: Run Tests

- [ ] Run feature tests
  ```bash
  php artisan test --filter AIServiceTest
  ```

- [ ] All tests should pass ✅

---

## 📋 Bước 11: Production Preparation (Optional)

- [ ] Setup queue workers with Supervisor
  ```ini
  [program:megalearning-worker]
  command=php /path/to/artisan queue:work --tries=3
  autostart=true
  autorestart=true
  ```

- [ ] Enable Redis for queue
  ```env
  QUEUE_CONNECTION=redis
  REDIS_HOST=127.0.0.1
  ```

- [ ] Setup cron for scheduled tasks
  ```bash
  * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
  ```

- [ ] Configure proper logging
  ```env
  LOG_CHANNEL=daily
  LOG_LEVEL=error
  ```

- [ ] Add rate limiting for AI calls

- [ ] Setup monitoring (e.g., Laravel Telescope)

---

## 🎉 Completion Checklist

- [ ] ✅ AI responds to messages
- [ ] ✅ Chat rooms working
- [ ] ✅ API endpoints functional
- [ ] ✅ Broadcasting setup (if needed)
- [ ] ✅ Tests passing
- [ ] ✅ Documentation reviewed

---

## 🐛 Common Issues & Solutions

### ❌ AI không phản hồi

**Possible causes:**
1. OPENAI_API_KEY chưa set hoặc sai
2. Queue worker không chạy
3. AI không phải member của room

**Solutions:**
```bash
# 1. Check API key
php artisan tinker
>>> config('services.openai.api_key')

# 2. Start queue worker
php artisan queue:work

# 3. Check AI membership
>>> $room = \App\Models\ChatRoom::find(1);
>>> $room->members()->where('role', 'bot')->exists()
```

### ❌ "Class 'OpenAI' not found"

**Solution:**
```bash
# AIService uses Http facade, không cần package riêng
# Đảm bảo đã chạy:
composer install
php artisan config:clear
```

### ❌ Messages không realtime

**Solution:**
```bash
# 1. Check broadcasting config
php artisan config:clear

# 2. Verify Pusher/Reverb running
# See docs/BROADCASTING_SETUP.md

# 3. Check browser console for errors
```

### ❌ Database migration errors

**Solution:**
```bash
# Fresh migration
php artisan migrate:fresh

# Or rollback and re-run
php artisan migrate:rollback
php artisan migrate
```

---

## 📞 Need Help?

1. **Check logs**: `storage/logs/laravel.log`
2. **Review docs**: 
   - `docs/AI_CHAT_GUIDE.md` - Full guide
   - `docs/QUICK_START_AI_CHAT.md` - Quick start
   - `docs/BROADCASTING_SETUP.md` - Realtime setup
3. **OpenAI status**: https://status.openai.com
4. **Test command**: `php artisan chat:test-ai`

---

## 🚀 You're All Set!

Khi tất cả checkboxes đều ✅, hệ thống chat AI của bạn đã sẵn sàng!

**Next steps:**
- Thử chat với AI
- Customize AI personality
- Setup broadcasting cho realtime
- Deploy to production

**Happy coding! 🎉🤖**
