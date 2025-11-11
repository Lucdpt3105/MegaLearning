# 🔴 Setup Broadcasting cho Realtime Chat

## 📌 Tổng Quan

Để chat realtime hoạt động, bạn cần cấu hình **Laravel Broadcasting** với một trong các drivers:
- **Pusher** (dễ nhất, có free tier)
- **Laravel Reverb** (Laravel 11 mới, built-in)
- **Soketi** (self-hosted, free)
- **Redis + Socket.io** (advanced)

---

## 🚀 Option 1: Pusher (Recommended cho Development)

### **1. Tạo tài khoản Pusher**

1. Đăng ký tại: https://pusher.com
2. Tạo app mới
3. Copy credentials

### **2. Cài đặt Pusher PHP SDK**

```bash
composer require pusher/pusher-php-server
```

### **3. Cấu hình `.env`**

```bash
BROADCAST_CONNECTION=pusher

PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=ap1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### **4. Uncomment trong `config/app.php`**

```php
'providers' => [
    // ...
    App\Providers\BroadcastServiceProvider::class,
],
```

### **5. Cài đặt Pusher JS**

```bash
npm install --save-dev pusher-js laravel-echo
```

### **6. Cấu hình `resources/js/bootstrap.js`**

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});
```

### **7. Build assets**

```bash
npm run dev
```

---

## 🔵 Option 2: Laravel Reverb (Laravel 11+)

### **1. Cài đặt Reverb**

```bash
composer require laravel/reverb
php artisan reverb:install
```

### **2. Cấu hình `.env`**

```bash
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### **3. Start Reverb Server**

```bash
php artisan reverb:start
```

### **4. Cấu hình Frontend**

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
```

---

## 🟢 Option 3: Soketi (Self-hosted, Free)

### **1. Cài đặt Soketi**

```bash
npm install -g @soketi/soketi
```

### **2. Tạo file `soketi.json`**

```json
{
    "debug": true,
    "port": 6001,
    "appManager.array.apps": [
        {
            "id": "app-id",
            "key": "app-key",
            "secret": "app-secret"
        }
    ]
}
```

### **3. Start Soketi**

```bash
soketi start --config=soketi.json
```

### **4. Cấu hình như Pusher**

```bash
BROADCAST_CONNECTION=pusher

PUSHER_APP_ID=app-id
PUSHER_APP_KEY=app-key
PUSHER_APP_SECRET=app-secret
PUSHER_HOST=127.0.0.1
PUSHER_PORT=6001
PUSHER_SCHEME=http
```

---

## 🎯 Tạo Broadcast Service Provider

### **1. Tạo provider nếu chưa có**

```bash
php artisan make:provider BroadcastServiceProvider
```

### **2. File `app/Providers/BroadcastServiceProvider.php`**

```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Broadcast::routes(['middleware' => ['auth:sanctum']]);

        require base_path('routes/channels.php');
    }
}
```

### **3. Tạo file `routes/channels.php`**

```php
<?php

use Illuminate\Support\Facades\Broadcast;

// Chat room channel - only members can join
Broadcast::channel('chat-room.{roomId}', function ($user, $roomId) {
    // Check if user is member of this room
    $room = \App\Models\ChatRoom::find($roomId);
    
    if (!$room) {
        return false;
    }
    
    // Public rooms - allow all
    if ($room->room_type === 'group') {
        return true;
    }
    
    // Private rooms - check membership
    return $room->members->contains($user->id);
});
```

---

## 💻 Frontend Integration (Vue/React/Vanilla JS)

### **Vue Example**

```vue
<script setup>
import { onMounted, ref } from 'vue';

const messages = ref([]);
const roomId = 1;

onMounted(() => {
    // Listen for new messages
    window.Echo.private(`chat-room.${roomId}`)
        .listen('MessageSent', (e) => {
            messages.value.push(e.message);
        });
});
</script>
```

### **Vanilla JS Example**

```javascript
// Listen for messages in room 1
Echo.private('chat-room.1')
    .listen('MessageSent', (e) => {
        console.log('New message:', e.message);
        appendMessageToUI(e.message);
    });

function sendMessage(text) {
    fetch('/api/chat/rooms/1/messages', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message_text: text })
    });
}
```

---

## ✅ Testing Broadcasting

### **1. Test Event**

```bash
php artisan tinker

$room = \App\Models\ChatRoom::first();
$message = \App\Models\ChatMessage::first();

broadcast(new \App\Events\MessageSent($message));
```

### **2. Check Pusher Dashboard**

- Vào Pusher dashboard → Debug Console
- Gửi message → xem event appear

### **3. Browser Console**

```javascript
// Check Echo is connected
console.log(Echo);

// Manual subscribe
Echo.private('chat-room.1')
    .listen('MessageSent', (e) => console.log(e));
```

---

## 🔧 Troubleshooting

### **Không nhận được events?**

1. Check `.env` config đúng chưa
2. Clear config cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```
3. Restart queue worker (nếu dùng queue)
4. Check browser console for errors

### **403 Forbidden khi subscribe?**

- Check `routes/channels.php` authorization logic
- Verify user is authenticated
- Check channel name format

### **Events broadcast nhưng không realtime?**

- Verify `BROADCAST_CONNECTION` không phải `log`
- Check Pusher/Reverb server đang chạy
- Verify frontend Echo config đúng

---

## 📦 Production Deployment

### **1. Queue Workers**

```bash
# Chạy queue worker để handle broadcasts
php artisan queue:work --tries=3
```

### **2. Supervisor Config**

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=2
```

### **3. SSL/TLS**

- Pusher: Tự động có SSL
- Reverb/Soketi: Cần setup nginx/apache reverse proxy

---

## 🎉 All Done!

Bây giờ bạn có:
- ✅ Realtime chat broadcasting
- ✅ AI auto-response
- ✅ Multi-room support
- ✅ Scalable architecture

**Test ngay thôi! 🚀**
