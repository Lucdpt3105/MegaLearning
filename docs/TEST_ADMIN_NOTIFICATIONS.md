# Hướng dẫn Test Thông báo Admin

## ✅ Đã tích hợp AdminNotificationService

Hệ thống thông báo admin đã được tích hợp hoàn toàn! Admin sẽ **tự động** nhận thông báo khi:

1. **🎓 Học sinh mới đăng ký** - `AuthController@register`
2. **📝 Học sinh nộp bài thi** - `Student/ExamController@submit`
3. **📚 Giáo viên tạo lớp học mới** - `Admin/CourseController@store`
4. **📄 Admin upload tài liệu** - `Admin/FileController@store`
5. **📄 Giáo viên upload tài liệu** - `Teacher/DocumentController@store`

---

## 🧪 Cách Test

### 1️⃣ Test Nhanh (Dùng Routes Test)

#### **Route 1: Test thông báo đơn**
```bash
# Đăng nhập với tài khoản admin, sau đó truy cập:
http://127.0.0.1:8000/test/admin-notification
```

**Kết quả mong đợi:**
```json
{
  "success": true,
  "message": "Đã gửi X thông báo cho admin!",
  "count": X,
  "instructions": "Kiểm tra chuông thông báo ở góc trên bên phải admin panel"
}
```

#### **Route 2: Test với nhiều loại thông báo**
```bash
# Truy cập:
http://127.0.0.1:8000/test/admin-notification/fake-data
```

**Kết quả mong đợi:**
- Sẽ tạo 5 loại thông báo khác nhau
- Thông báo sẽ hiện ngay ở chuông thông báo
- Click vào chuông để xem danh sách

---

### 2️⃣ Test Thực Tế (Tương tác người dùng)

#### **A. Test Đăng ký học sinh mới**

1. **Đăng xuất** khỏi tài khoản hiện tại
2. Truy cập: http://127.0.0.1:8000/register
3. Đăng ký tài khoản học sinh mới:
   - Name: Test Student
   - Email: teststudent@test.com
   - Password: password123
4. **Đăng nhập lại với tài khoản Admin**
5. Kiểm tra chuông thông báo → Sẽ thấy thông báo "Học sinh mới đăng ký"

---

#### **B. Test Nộp bài thi**

**Điều kiện:** Phải có exam sẵn và học sinh đã enroll vào class

1. Đăng nhập với tài khoản **student**
2. Truy cập: http://127.0.0.1:8000/student/exams
3. Chọn một bài thi và làm bài
4. Nộp bài thi
5. **Đăng nhập lại với tài khoản Admin**
6. Kiểm tra chuông thông báo → Sẽ thấy "Bài nộp mới từ [Tên học sinh]"

---

#### **C. Test Tạo lớp học mới**

1. Đăng nhập với tài khoản **admin**
2. Truy cập: http://127.0.0.1:8000/admin/courses/create
3. Điền form tạo lớp học:
   - Tên lớp: Test Class
   - Subject: (chọn một subject)
   - Teacher: (chọn một giáo viên)
   - Max students: 30
   - Start date: ngày hiện tại
4. Click "Tạo lớp học"
5. Kiểm tra chuông thông báo → Sẽ thấy "Lớp học mới: Test Class"

---

#### **D. Test Upload tài liệu (Admin)**

1. Đăng nhập với tài khoản **admin**
2. Truy cập: http://127.0.0.1:8000/admin/files/upload
3. Upload một file PDF/Word:
   - Title: Test Document
   - Folder: general/lecture/exam/homework
   - File: (chọn file từ máy)
4. Click "Upload"
5. Kiểm tra chuông thông báo → Sẽ thấy "Tài liệu mới: Test Document"

---

#### **E. Test Upload tài liệu (Teacher)**

1. Đăng nhập với tài khoản **teacher**
2. Truy cập: http://127.0.0.1:8000/teacher/documents/create
3. Upload tài liệu:
   - Title: Teacher Document
   - Subject: (chọn subject của giáo viên)
   - File: (chọn file)
4. Click "Upload"
5. **Đăng nhập lại với tài khoản Admin**
6. Kiểm tra chuông thông báo → Sẽ thấy "GV [Tên] vừa upload tài liệu"

---

## 📋 Checklist Test

**Test Routes (Nhanh):**
- [ ] `/test/admin-notification` - Hiện JSON success với count
- [ ] `/test/admin-notification/fake-data` - Tạo 5 thông báo test
- [ ] Chuông thông báo hiện số badge đỏ
- [ ] Click chuông → dropdown hiện danh sách thông báo
- [ ] Click "Xem tất cả" → chuyển đến `/notifications`

**Test Tương tác thực tế:**
- [ ] Đăng ký học sinh mới → Admin nhận thông báo
- [ ] Nộp bài thi → Admin nhận thông báo
- [ ] Tạo lớp học → Admin nhận thông báo
- [ ] Admin upload tài liệu → Admin nhận thông báo
- [ ] Teacher upload tài liệu → Admin nhận thông báo

**Test UI:**
- [ ] Badge số đếm chuông cập nhật realtime (mỗi 30s)
- [ ] Dropdown thông báo hiện 5 thông báo mới nhất
- [ ] Icon phù hợp với loại thông báo
- [ ] Thời gian hiển thị đúng (2 giờ trước, 1 ngày trước...)
- [ ] Click vào thông báo → chuyển đến URL liên quan
- [ ] Trang /notifications hiện tất cả thông báo với phân trang

---

## 🔧 Troubleshooting

### Không nhận được thông báo?

1. **Kiểm tra database:**
   ```sql
   SELECT * FROM notifications 
   WHERE user_id IN (SELECT id FROM users WHERE role = 'admin') 
   ORDER BY created_at DESC 
   LIMIT 10;
   ```

2. **Kiểm tra AdminNotificationService:**
   ```bash
   php artisan tinker
   ```
   ```php
   $service = app(\App\Services\AdminNotificationService::class);
   $service->notifyAdmins('Test', 'Test message', 'general', '/');
   // Nếu trả về > 0 là service hoạt động
   ```

3. **Kiểm tra role admin:**
   ```bash
   php artisan tinker
   ```
   ```php
   \App\Models\User::role('admin')->count();
   // Phải > 0
   ```

4. **Kiểm tra JavaScript console:**
   - Mở Chrome DevTools (F12)
   - Tab Console
   - Xem có lỗi gì không khi load admin panel

### Thông báo không hiện trong dropdown?

1. **Kiểm tra API endpoint:**
   ```bash
   # Đăng nhập admin, sau đó truy cập:
   http://127.0.0.1:8000/notifications/api/unread
   ```
   Phải trả về JSON với danh sách thông báo

2. **Kiểm tra Alpine.js:**
   - View source của admin layout
   - Tìm `x-data="adminNotification()"`
   - Kiểm tra function `loadNotifications()` có được gọi không

### Badge số không cập nhật?

1. **Kiểm tra setInterval:**
   - Mở Console
   - Xem có gọi `/notifications/api/unread` mỗi 30s không
   
2. **Force reload:**
   - Ctrl + Shift + R (hard reload)
   - Clear browser cache

---

## 📊 Logs và Monitoring

### Xem notifications trong database:
```sql
-- Xem 10 thông báo mới nhất cho admin
SELECT 
    n.id,
    n.title,
    n.type,
    n.created_at,
    u.name as admin_name
FROM notifications n
JOIN users u ON n.user_id = u.id
JOIN model_has_roles mhr ON u.id = mhr.model_id
JOIN roles r ON mhr.role_id = r.id
WHERE r.name = 'admin'
ORDER BY n.created_at DESC
LIMIT 10;
```

### Xem thống kê thông báo:
```sql
-- Đếm thông báo theo loại
SELECT type, COUNT(*) as count
FROM notifications
GROUP BY type
ORDER BY count DESC;
```

---

## 🎯 Kết luận

Hệ thống thông báo admin đã hoạt động **tự động** cho các sự kiện:
- ✅ Đăng ký người dùng mới (học sinh)
- ✅ Nộp bài thi (học sinh)
- ✅ Tạo lớp học (admin/teacher)
- ✅ Upload tài liệu (admin/teacher)

**Không cần can thiệp thêm** - hệ thống sẽ tự động gửi thông báo cho tất cả admin khi có các sự kiện trên!

---

## 📝 Notes

- Test routes chỉ dùng khi develop, **XÓA đi khi deploy production**
- Thông báo được lưu vào database, không mất khi reload trang
- Mỗi admin sẽ nhận một bản copy thông báo riêng
- Badge số cập nhật mỗi 30s (có thể thay đổi trong `adminNotification()` function)

---

**Chúc bạn test thành công! 🎉**
