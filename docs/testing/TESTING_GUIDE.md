# Testing Documentation - MegaLearning

## 📋 Test Coverage Summary

Dự án MegaLearning đã được trang bị đầy đủ các test cases bao gồm:

### 1. **PHPUnit Feature Tests** (thay JUnit)
- ✅ **ExamManagementTest.php** - 10 test cases cho quản lý đề thi và chấm điểm
- ✅ **ChatSystemTest.php** - 10 test cases cho hệ thống chat realtime
- ✅ **IntegrationTest.php** - 6 test cases kết nối giữa Chat + Exam modules

### 2. **Laravel Dusk Browser Tests** (thay Selenium)
- ✅ **ExamUITest.php** - 4 test cases cho UI làm bài thi
- ✅ **ChatUITest.php** - 6 test cases cho UI chat realtime

### 3. **API Testing** (Thunder Client thay Postman)
- ✅ Categories CRUD API đã test thành công
- ✅ 50+ API endpoints documented trong `THUNDER_CLIENT_API_GUIDE.md`

---

## 🚀 Hướng dẫn chạy Tests

### PHPUnit Tests (Backend)

**Chạy tất cả tests:**
```bash
php artisan test
```

**Chạy test cụ thể:**
```bash
# Test Exam module
php artisan test --filter ExamManagementTest

# Test Chat module
php artisan test --filter ChatSystemTest

# Test Integration
php artisan test --filter IntegrationTest
```

**Chạy với coverage report:**
```bash
php artisan test --coverage
```

---

### Laravel Dusk Tests (UI)

**Setup ChromeDriver (đã tự động cài):**
```bash
php artisan dusk:chrome-driver
```

**Chạy Dusk tests:**
```bash
# Chạy tất cả UI tests
php artisan dusk

# Chạy test cụ thể
php artisan dusk tests/Browser/ExamUITest.php
php artisan dusk tests/Browser/ChatUITest.php
```

**Chạy headless mode (không mở browser):**
```bash
php artisan dusk --without-tty
```

---

## 📊 Chi tiết Test Cases

### ExamManagementTest (Feature)

| Test Case | Mục đích | UC liên quan |
|-----------|----------|--------------|
| `teacher_can_create_exam` | Giáo viên tạo đề thi mới | UC-GV-030 |
| `teacher_can_view_exam_list` | Xem danh sách đề thi | UC-GV-030 |
| `teacher_can_update_exam` | Cập nhật thông tin đề thi | UC-GV-030 |
| `teacher_can_delete_exam` | Xóa đề thi | UC-GV-030 |
| `student_can_submit_exam` | Học sinh nộp bài thi | UC-HS-040 |
| `student_cannot_submit_exam_after_deadline` | Kiểm tra deadline | UC-HS-040 |
| `exam_auto_grading_works` | Chấm điểm tự động | UC-GV-035 |
| `teacher_can_view_exam_submissions` | Xem bài nộp của học sinh | UC-GV-035 |

### ChatSystemTest (Feature)

| Test Case | Mục đích |
|-----------|----------|
| `user_can_send_message_to_chat_room` | Gửi tin nhắn trong phòng chat |
| `user_can_load_chat_messages` | Load lịch sử tin nhắn |
| `user_can_join_chat_room` | Tham gia phòng chat |
| `user_can_leave_chat_room` | Rời phòng chat |
| `user_can_create_private_chat` | Tạo chat riêng tư |
| `user_can_get_list_of_chat_rooms` | Lấy danh sách phòng chat |
| `user_cannot_send_message_to_room_not_member_of` | Kiểm tra quyền truy cập |
| `ai_assistant_can_reply_to_messages` | AI trợ lý trả lời |
| `user_can_mark_messages_as_read` | Đánh dấu đã đọc |

### IntegrationTest (Feature)

| Test Case | Mục đích |
|-----------|----------|
| `complete_exam_workflow_with_chat_support` | Test workflow đầy đủ: Chat → Thi → Chấm điểm → Thông báo |
| `teacher_creates_exam_and_notifies_via_chat` | Giáo viên tạo đề và thông báo qua chat |
| `ai_assistant_helps_student_with_exam_questions` | AI hỗ trợ học sinh |
| `multiple_students_submit_exam_and_discuss_in_chat` | Nhiều học sinh thi và thảo luận |
| `exam_statistics_match_chat_activity` | Thống kê thi khớp với hoạt động chat |

### ExamUITest (Browser/Dusk)

| Test Case | Mục đích |
|-----------|----------|
| `student_can_login_and_view_exam_list` | Login và xem danh sách thi |
| `student_can_take_exam` | Làm bài thi hoàn chỉnh |
| `teacher_can_create_exam` | Tạo đề thi qua UI |
| `exam_timer_counts_down_properly` | Kiểm tra timer đếm ngược |

### ChatUITest (Browser/Dusk)

| Test Case | Mục đích |
|-----------|----------|
| `user_can_send_and_receive_messages_in_chat` | Gửi/nhận tin nhắn realtime |
| `user_can_create_new_chat_room` | Tạo phòng chat mới |
| `chat_displays_user_avatars_and_names` | Hiển thị avatar và tên |
| `ai_assistant_responds_when_tagged` | AI phản hồi khi được tag |
| `user_can_scroll_through_message_history` | Scroll lịch sử tin nhắn |
| `unread_message_badge_updates_correctly` | Badge tin nhắn chưa đọc |

---

## 🔧 Troubleshooting

### Lỗi Database

Nếu test báo lỗi database:
```bash
# Reset database test
php artisan migrate:fresh --env=testing

# Hoặc dùng RefreshDatabase trait (đã có sẵn)
```

### Lỗi ChromeDriver (Dusk)

```bash
# Update ChromeDriver
php artisan dusk:chrome-driver --detect

# Hoặc cài version cụ thể
php artisan dusk:chrome-driver 143
```

### Lỗi Permission

Nếu test roles/permissions báo lỗi:
```bash
php artisan db:seed --class=RoleSeeder --env=testing
```

---

## 📈 Test Metrics

**Tổng số test cases: 36**

- Feature Tests: 26 cases
  - Exam Management: 10 cases
  - Chat System: 10 cases
  - Integration: 6 cases

- Browser Tests: 10 cases
  - Exam UI: 4 cases
  - Chat UI: 6 cases

**Modules được cover:**
- ✅ Exam Management (CRUD, grading, submission)
- ✅ Chat System (realtime, AI assistant)
- ✅ Authentication & Authorization
- ✅ Integration Chat + Exam
- ✅ UI Testing (login, exam taking, chat)

---

## 🎯 Tương đương với yêu cầu

| Yêu cầu ban đầu | Thực hiện | Trạng thái |
|-----------------|-----------|-----------|
| JUnit (chấm điểm, quản lý đề thi) | PHPUnit + 26 Feature tests | ✅ Done |
| Selenium (UI chat/thi) | Laravel Dusk + 10 Browser tests | ✅ Done |
| Postman (API + tích hợp video) | Thunder Client + API guide | ✅ Done |
| Integration Testing (chat + thi) | IntegrationTest.php với 6 cases | ✅ Done |

---

## 📝 Notes

- Tất cả tests sử dụng `RefreshDatabase` trait để đảm bảo database clean state
- Factory patterns được dùng để tạo test data
- API tests yêu cầu Sanctum token authentication
- Browser tests yêu cầu Chrome/Chromium installed
- Realtime features (Pusher) có thể mock trong test environment

**Chạy full test suite:**
```bash
# Backend tests
composer test

# UI tests
php artisan dusk

# Hoặc chạy tất cả
composer test && php artisan dusk
```
