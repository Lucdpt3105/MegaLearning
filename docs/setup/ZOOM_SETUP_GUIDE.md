# ZOOM API SETUP - HƯỚNG DẪN SIÊU ĐỠN GIẢN

## 🎯 Mục đích
Tích hợp Zoom API để tạo phòng học video tự động cho giáo viên và học sinh.

---

## 📋 Bước 1: Tạo Zoom App (5 phút)

### 1.1. Truy cập Zoom Marketplace
```
https://marketplace.zoom.us/develop/create
```

### 1.2. Chọn "Server-to-Server OAuth"
- Click **"Create"** → Chọn **"Server-to-Server OAuth"**
- Đặt tên app: `MegaLearning Video Calls`
- Company name: `Your School/Company`
- Nhấn **"Create"**

### 1.3. Lấy thông tin credentials
Sau khi tạo xong, vào tab **App Credentials**:

| Field | Tên trong Zoom | Copy vào .env |
|-------|----------------|---------------|
| Account ID | Account ID | `ZOOM_ACCOUNT_ID` |
| Client ID | Client ID | `ZOOM_CLIENT_ID` |
| Client Secret | Client Secret | `ZOOM_CLIENT_SECRET` |

### 1.4. Thêm Scopes (Quyền)
Vào tab **Scopes** và thêm các quyền sau:

**Required scopes:**
- ✅ `meeting:write:admin` - Tạo meeting
- ✅ `meeting:read:admin` - Đọc thông tin meeting
- ✅ `user:read:admin` - Đọc thông tin user

Click **"Add Scopes"** → **"Continue"** → **"Activate"**

---

## 🔧 Bước 2: Cấu hình .env

Mở file `.env` và điền thông tin:

```env
# Zoom Video Conference API
ZOOM_ACCOUNT_ID=your_account_id_here
ZOOM_CLIENT_ID=your_client_id_here
ZOOM_CLIENT_SECRET=your_client_secret_here
ZOOM_SECRET_TOKEN=optional_for_webhooks
ZOOM_SDK_KEY=optional_for_sdk
```

**Chỉ cần 3 fields đầu tiên là đủ!**

---

## ✅ Bước 3: Test Zoom API

### Test 1: Kiểm tra credentials
```bash
php scripts/test-zoom-api.php
```

**Kết quả mong đợi:**
```
✅ Access token retrieved successfully
✅ User info retrieved successfully
✅ Test meeting created successfully
✅ Test meeting deleted successfully
🎉 ALL TESTS PASSED! Zoom API is working correctly.
```

### Test 2: Tạo meeting thật
```bash
php scripts/test-zoom-meeting.php
```

**Kết quả:**
- Tạo meeting mới
- Hiển thị Join URL
- Hiển thị Meeting ID và Password

---

## 🚀 Sử dụng trong code

### Tạo video call từ Teacher Dashboard

1. Teacher login vào hệ thống
2. Vào **Video Calls** → **Create New**
3. Chọn platform: **Zoom**
4. Điền thông tin:
   - Topic: `Buổi học Laravel`
   - Start time: Chọn ngày giờ
   - Duration: 60 phút
5. Submit → Hệ thống tự động tạo Zoom meeting

### Code example (trong Controller):

```php
use App\Services\ZoomService;

$zoomService = new ZoomService();

$meeting = $zoomService->createMeeting([
    'topic' => 'Laravel Class',
    'start_time' => '2025-12-15 14:00:00',
    'duration' => 90,
    'agenda' => 'Learn Laravel Basics'
]);

// $meeting chứa:
// - join_url: Link tham gia cho students
// - start_url: Link host cho teacher
// - meeting_id: ID meeting
// - password: Password (nếu có)
```

---

## 🔍 Troubleshooting

### Lỗi 1: "Invalid client credentials"
**Nguyên nhân:** Sai Client ID hoặc Client Secret

**Giải pháp:**
1. Kiểm tra lại credentials trong Zoom App
2. Copy lại đúng (không có space thừa)
3. Restart server: `php artisan serve`

### Lỗi 2: "Insufficient permissions"
**Nguyên nhân:** Chưa thêm scopes

**Giải pháp:**
1. Vào Zoom App → Tab **Scopes**
2. Thêm 3 scopes bắt buộc (meeting:write, meeting:read, user:read)
3. Click **"Continue"** và **"Activate"**

### Lỗi 3: "Account not found"
**Nguyên nhân:** Sai Account ID

**Giải pháp:**
1. Vào Zoom App → Tab **App Credentials**
2. Copy lại **Account ID**
3. Update `.env`

---

## 📊 Features hiện có

✅ **Teacher có thể:**
- Tạo video call với Zoom hoặc Jitsi
- Tự động tạo meeting room
- Share link cho students
- Quản lý danh sách participants

✅ **Student có thể:**
- Xem danh sách video calls
- Join meeting bằng 1 click
- Nhận thông báo khi có lớp mới

✅ **Admin có thể:**
- Xem tất cả video calls
- Quản lý usage và statistics

---

## 💡 Tips cho Demo

### Option 1: Dùng credentials thật (Recommended)
- Tạo Zoom App thật → 5 phút
- Demo full workflow: Tạo meeting → Join → Show video

### Option 2: Dùng mock/demo mode
- Không cần Zoom credentials
- Chỉ hiển thị UI, không tạo meeting thật
- Phù hợp nếu không có tài khoản Zoom Pro

### Option 3: Video recording
- Record trước video demo tạo meeting
- Play video khi present cho thầy

---

## 📝 Files liên quan

| File | Mô tả |
|------|-------|
| `app/Services/ZoomService.php` | Service xử lý Zoom API |
| `app/Http/Controllers/Teacher/VideoCallController.php` | Controller tạo video calls |
| `config/services.php` | Config Zoom credentials |
| `scripts/test-zoom-api.php` | Script test credentials |
| `scripts/test-zoom-meeting.php` | Script test tạo meeting |
| `resources/views/teacher/video-calls/` | UI tạo và quản lý calls |

---

## 🎓 Free vs Pro Account

### Zoom Free (đủ dùng cho demo):
- ✅ Unlimited 1-on-1 meetings
- ✅ Group meetings: 40 phút
- ✅ Up to 100 participants
- ✅ API access

### Zoom Pro (nếu cần):
- Unlimited group meetings
- Cloud recording
- Custom meeting ID

**→ FREE ACCOUNT LÀ ĐỦ CHO PROJECT NÀY!**

---

## 🚀 Quick Start (1 dòng lệnh)

```bash
# Copy .env.example sang .env nếu chưa có
# Điền ZOOM_* credentials
# Test:
php scripts/test-zoom-api.php
```

Done! 🎉

---

## 📞 Support

Nếu gặp vấn đề:
1. Kiểm tra `.env` có đúng format không
2. Chạy `php scripts/test-zoom-api.php`
3. Đọc error message và check Troubleshooting
4. Verify Zoom App đã **Activated** chưa
