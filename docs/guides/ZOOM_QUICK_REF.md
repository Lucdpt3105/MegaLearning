# ZOOM API - QUICK REFERENCE

## 🎯 Tóm tắt siêu nhanh

**Mục đích:** Tạo phòng học video Zoom tự động cho giáo viên

**Thời gian setup:** 5 phút

**Cần gì:**
1. Tài khoản Zoom (Free cũng được)
2. 3 credentials: Account ID, Client ID, Client Secret

---

## 🚀 Setup trong 3 bước

### 1️⃣ Tạo Zoom App
```
👉 https://marketplace.zoom.us/develop/create
→ Chọn "Server-to-Server OAuth"
→ Đặt tên "MegaLearning"
→ Create
```

### 2️⃣ Lấy credentials & thêm quyền
```
Tab "App Credentials" → Copy 3 giá trị
Tab "Scopes" → Thêm 3 quyền:
  ✅ meeting:write:admin
  ✅ meeting:read:admin  
  ✅ user:read:admin
→ Click "Activate"
```

### 3️⃣ Config & Test
```bash
# Option A: Tự động (Recommended)
scripts\setup-zoom.bat

# Option B: Thủ công
# Mở .env, điền:
ZOOM_ACCOUNT_ID=abc123
ZOOM_CLIENT_ID=xyz456
ZOOM_CLIENT_SECRET=secret789

# Test
php scripts/test-zoom-api.php
```

---

## ✅ Kiểm tra nhanh

```bash
# Check status
php scripts/check-zoom-config.php

# Test tạo meeting
php scripts/test-zoom-meeting.php
```

---

## 💻 Sử dụng trong code

```php
use App\Services\ZoomService;

$zoom = new ZoomService();

// Tạo meeting
$meeting = $zoom->createMeeting([
    'topic' => 'Laravel Class',
    'start_time' => '2025-12-15 14:00:00',
    'duration' => 60
]);

// Kết quả:
// $meeting['join_url'] → Link cho students
// $meeting['start_url'] → Link cho teacher  
// $meeting['meeting_id'] → Meeting ID
// $meeting['password'] → Password
```

---

## 🎓 Teacher UI Flow

1. Login as Teacher
2. Menu → **Video Calls** → **Create New**
3. Chọn platform: **Zoom** ⚡
4. Điền thông tin:
   - Topic: `Buổi học Laravel`
   - Date & Time
   - Duration: 60 phút
5. **Submit** → Tự động tạo Zoom meeting

Student nhận link → Click join → Vào ngay Zoom

---

## 🐛 Troubleshooting

| Lỗi | Nguyên nhân | Fix |
|-----|-------------|-----|
| Invalid credentials | Sai Client ID/Secret | Copy lại từ Zoom App |
| Insufficient permissions | Thiếu scopes | Thêm 3 scopes bắt buộc |
| Account not found | Sai Account ID | Check lại App Credentials |
| App not activated | Chưa activate | Click "Activate" trong Zoom App |

---

## 📊 Feature checklist

- ✅ ZoomService class có sẵn
- ✅ VideoCallController đã tích hợp
- ✅ Teacher UI để tạo calls
- ✅ Student UI để join
- ✅ Auto-generate meeting password
- ✅ Support Jitsi fallback (không cần credentials)

---

## 💡 Demo cho thầy

**Option 1: Full setup (5 phút)**
- Tạo Zoom App thật
- Demo tạo meeting + join

**Option 2: Mock mode (0 phút)**
- Chọn Jitsi instead of Zoom
- Vẫn show UI workflow, không cần credentials

**Option 3: Video demo**
- Record trước việc tạo meeting
- Play khi present

---

## 📁 Files quan trọng

```
app/Services/ZoomService.php          → Core service
scripts/setup-zoom.bat                → Setup wizard
scripts/check-zoom-config.php         → Check status
scripts/test-zoom-api.php             → Test credentials
ZOOM_SETUP_GUIDE.md                   → Full guide
```

---

## 🎯 Quick Commands

```bash
# Setup
scripts\setup-zoom.bat

# Check
php scripts/check-zoom-config.php

# Test
php scripts/test-zoom-api.php
php scripts/test-zoom-meeting.php

# View guide
start ZOOM_SETUP_GUIDE.md
```

---

**→ ZOOM FREE ACCOUNT LÀ ĐỦ!** 🎉
