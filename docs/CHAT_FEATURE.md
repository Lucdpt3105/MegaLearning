# Chat Feature Documentation

## 📁 Cấu Trúc File Chat Realtime

### **Models** (app/Models/)
- `ChatRoom.php` - Model cho phòng chat
- `ChatMessage.php` - Model cho tin nhắn

### **Controllers**
- `app/Http/Controllers/ChatController.php` - Web Controller
- `app/Http/Controllers/Api/ChatApiController.php` - API Controller

### **Events**
- `app/Events/MessageSent.php` - Broadcasting event cho realtime

### **Migrations** (database/migrations/)
- `2025_11_11_000001_create_chat_rooms_table.php`
- `2025_11_11_000002_create_chat_messages_table.php`
- `2025_11_11_000003_create_chat_room_members_table.php`

### **Views** (resources/views/chat/)
- `index.blade.php` - Danh sách chat rooms
- `room.blade.php` - Giao diện chat room

### **Routes**
- Web Routes: `/chat/*` (web.php)
- API Routes: `/api/v1/chat/*` (api.php)

---

## 🗄️ Database Schema

### Table: `chat_rooms`
```sql
- room_id (PK)
- room_name
- room_type (group, private, subject)
- subject_id (FK -> subjects)
- created_by (FK -> users)
- is_active
- timestamps
```

### Table: `chat_messages`
```sql
- message_id (PK)
- room_id (FK -> chat_rooms)
- user_id (FK -> users)
- message_text
- message_type (text, image, file, system)
- file_url
- is_edited
- is_deleted
- timestamps
```

### Table: `chat_room_members`
```sql
- id (PK)
- room_id (FK -> chat_rooms)
- user_id (FK -> users)
- role (admin, member)
- joined_at
- timestamps
```

---

## 🔌 API Endpoints

### Web Routes (Authenticated)
```
GET    /chat                      -> Danh sách rooms
GET    /chat/room/{roomId}        -> Chi tiết room
POST   /chat/room                 -> Tạo room mới
POST   /chat/room/{roomId}/message -> Gửi tin nhắn
POST   /chat/room/{roomId}/join   -> Tham gia room
POST   /chat/room/{roomId}/leave  -> Rời room
```

### API Routes (Sanctum Auth)
```
GET    /api/v1/chat/rooms                    -> Lấy danh sách rooms
POST   /api/v1/chat/rooms                    -> Tạo room mới
GET    /api/v1/chat/rooms/{roomId}/messages  -> Lấy tin nhắn
POST   /api/v1/chat/rooms/{roomId}/messages  -> Gửi tin nhắn
POST   /api/v1/chat/rooms/{roomId}/join      -> Tham gia room
POST   /api/v1/chat/rooms/{roomId}/leave     -> Rời room
DELETE /api/v1/chat/messages/{messageId}     -> Xóa tin nhắn
```

---

## 🚀 Hướng Dẫn Sử Dụng

### 1. Chạy Migration
```bash
php artisan migrate
```

### 2. (Optional) Cài đặt Broadcasting cho Realtime

**Option A: Sử dụng Laravel Reverb (Recommended for Laravel 11)**
```bash
php artisan install:broadcasting
```

**Option B: Sử dụng Pusher**
```bash
composer require pusher/pusher-php-server
npm install --save-dev laravel-echo pusher-js
```

Cấu hình `.env`:
```env
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=ap1
```

### 3. Uncomment Broadcasting Code

Trong `ChatController.php` và `ChatApiController.php`, uncomment dòng:
```php
broadcast(new MessageSent($message))->toOthers();
```

Trong `chat/room.blade.php`, uncomment code Laravel Echo:
```javascript
Echo.private('chat-room.' + roomId)
    .listen('.message.sent', (e) => {
        appendMessage(e);
    });
```

### 4. Config Broadcasting Channels

Tạo file `routes/channels.php`:
```php
Broadcast::channel('chat-room.{roomId}', function ($user, $roomId) {
    $room = \App\Models\ChatRoom::find($roomId);
    return $room && $room->members->contains('id', $user->id);
});
```

---

## 💡 Tính Năng

### ✅ Đã Implement
- [x] Tạo/Xem chat rooms
- [x] Gửi/Nhận tin nhắn (AJAX)
- [x] Phân quyền room (admin/member)
- [x] Join/Leave room
- [x] Chat riêng, nhóm, theo môn học
- [x] API RESTful đầy đủ
- [x] Soft delete messages
- [x] Pagination messages

### 🔜 Cần Implement Thêm
- [ ] Real-time broadcasting (Pusher/Reverb)
- [ ] Upload files/images
- [ ] Typing indicators
- [ ] Online status
- [ ] Read receipts
- [ ] Emoji reactions
- [ ] Search messages
- [ ] Pin messages
- [ ] Mute notifications

---

## 🧪 Testing API

### Create Room
```bash
POST /api/v1/chat/rooms
Authorization: Bearer {token}
Content-Type: application/json

{
  "room_name": "Laravel Study Group",
  "room_type": "group",
  "members": [2, 3, 4]
}
```

### Send Message
```bash
POST /api/v1/chat/rooms/1/messages
Authorization: Bearer {token}
Content-Type: application/json

{
  "message_text": "Hello everyone!",
  "message_type": "text"
}
```

---

## 📝 Notes

1. **Authentication**: Chat routes yêu cầu user đã login (middleware: auth)
2. **Authorization**: Chỉ members của room mới có thể xem/gửi tin nhắn
3. **Real-time**: Hiện tại dùng AJAX polling, cần config Broadcasting cho thực sự realtime
4. **Scalability**: Nên dùng Queue cho sending notifications và Redis cho caching

---

Tạo bởi: GitHub Copilot
Ngày: {{ date('Y-m-d') }}
