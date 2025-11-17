# Cải Tiến Chức Năng Tạo Đề Thi 🔥

## Tổng Quan
Phiên bản mới bổ sung 2 tính năng lớn:
1. **Bảo mật đề thi** - Chống gian lận và kiểm soát truy cập
2. **Tạo đề tự động** - Hệ thống tự động chọn câu hỏi từ ngân hàng

---

## 1. Bảo Mật Đề Thi 🔒

### A. Kiểm soát truy cập

#### Mã truy cập (Access Code)
- **Mục đích**: Chỉ học sinh có mã mới được vào làm bài
- **Cách dùng**:
  - Bật checkbox "Yêu cầu mã truy cập"
  - Hệ thống tự tạo mã ngẫu nhiên (6 ký tự) hoặc tự đặt mã
  - Chia sẻ mã cho học sinh qua email/chat
- **Database**: `exams.access_code`, `exams.require_access_code`

#### Giới hạn lớp học
- **Mục đích**: Chỉ học sinh đã đăng ký lớp mới làm được
- **Mặc định**: Bật (recommended)
- **Database**: `exams.restrict_to_class`

### B. Phát hiện gian lận 🚨

#### Phát hiện đổi tab
- **Chức năng**: Cảnh báo khi học sinh chuyển sang tab/cửa sổ khác
- **Triển khai**: JavaScript detect `blur`/`focus` events
- **Database**: `exams.detect_tab_switch`

#### Phát hiện đổi thiết bị
- **Chức năng**: Khóa bài làm nếu học sinh đổi máy tính/điện thoại
- **Triển khai**: Lưu device fingerprint (IP, User-Agent, Screen Resolution)
- **Database**: `exams.detect_device_change`

#### Khóa khi thoát ứng dụng
- **Chức năng**: Tự động khóa nếu học sinh thoát quá lâu
- **Tham số**: Thời gian tối đa (5-300 giây)
- **Use case**: Học sinh đóng app, mở Google tìm đáp án
- **Database**: `exams.lock_on_exit`, `exams.max_exit_time`

### C. Giám sát nâng cao 📹 (Tùy chọn)

#### Yêu cầu bật camera
- **Chức năng**: Học sinh phải bật webcam trong khi làm bài
- **Triển khai**: WebRTC API để access camera
- **Database**: `exams.require_camera`

#### Ghi màn hình
- **Chức năng**: Ghi lại toàn bộ hoạt động trên màn hình
- **Triển khai**: Screen Recording API hoặc third-party service
- **Database**: `exams.require_screen_recording`

---

## 2. Tạo Đề Tự Động ⚡

### A. Phân bổ theo mức độ Bloom

Giáo viên nhập số lượng câu hỏi cho mỗi cấp độ:
- **Nhận biết** (Bloom Level 1): Nhớ, nhận diện
- **Thông hiểu** (Bloom Level 2): Hiểu, giải thích
- **Vận dụng** (Bloom Level 3): Áp dụng, sử dụng
- **Vận dụng cao** (Bloom Level 4+): Phân tích, tổng hợp, đánh giá

**Ví dụ**:
```
Nhận biết: 10 câu
Thông hiểu: 15 câu
Vận dụng: 5 câu
Vận dụng cao: 3 câu
```

### B. Phân bổ theo loại câu hỏi

- **Trắc nghiệm**: Multiple choice questions
- **Tự luận**: Essay questions

**Hệ thống sẽ ưu tiên**:
1. Lấy đủ theo Bloom level trước
2. Bổ sung theo type nếu chưa đủ

### C. Chọn chương/bài

- **Chức năng**: Lọc câu hỏi theo topics cụ thể
- **AJAX Loading**: Danh sách topics tải động khi chọn môn học
- **API Endpoint**: `GET /teacher/subjects/{subject}/topics`
- **Để trống**: Chọn từ tất cả chương/bài

### D. Thuật toán Auto-Generate

```php
1. Lấy câu hỏi theo Bloom Level (random):
   - Level 1: X câu
   - Level 2: Y câu
   - Level 3: Z câu
   - Level 4+: W câu

2. Bổ sung theo Type (nếu chưa đủ):
   - Multiple choice: M câu
   - Essay: N câu

3. Filter theo Topics (nếu có)

4. Loại trừ câu đã chọn

5. Attach vào exam với order và points
```

**Code**: `ExamController::autoGenerateQuestions()`

---

## Database Schema

### Migration: `add_security_fields_to_exams_table`

```sql
ALTER TABLE exams ADD COLUMN:
- access_code VARCHAR(20) NULL
- require_access_code BOOLEAN DEFAULT FALSE
- restrict_to_class BOOLEAN DEFAULT TRUE
- detect_cheating BOOLEAN DEFAULT FALSE
- detect_tab_switch BOOLEAN DEFAULT FALSE
- detect_device_change BOOLEAN DEFAULT FALSE
- lock_on_exit BOOLEAN DEFAULT FALSE
- max_exit_time INT NULL (seconds)
- require_camera BOOLEAN DEFAULT FALSE
- require_screen_recording BOOLEAN DEFAULT FALSE
- is_auto_generated BOOLEAN DEFAULT FALSE
- auto_gen_criteria JSON NULL
```

---

## API Endpoints

### GET /teacher/subjects/{subject}/topics
**Response**:
```json
[
  {
    "id": 1,
    "name": "Chương 1: Hàm số",
    "questions_count": 45
  },
  {
    "id": 2,
    "name": "Chương 2: Phương trình",
    "questions_count": 32
  }
]
```

---

## UI/UX

### Form Tạo Đề Thi - Sections

1. **Thông tin cơ bản** (existing)
2. **Thiết lập điểm** (existing)
3. **Lịch thi** (existing)
4. **Thiết lập nâng cao** (existing)
5. **🆕 Cài đặt bảo mật** 🔥
   - Mã truy cập
   - Chống gian lận
   - Giám sát nâng cao
6. **🆕 Tạo đề tự động** 🔥
   - Phân bổ Bloom levels
   - Phân bổ loại câu hỏi
   - Chọn chương/bài

### JavaScript Features

```javascript
// Toggle security options
toggleCheatingOptions()

// Toggle auto-generate section
toggleAutoGenerate()

// AJAX load topics
loadTopicsForSubject()
```

---

## Workflow

### Tạo đề với bảo mật
1. Nhập thông tin cơ bản
2. Bật "Yêu cầu mã truy cập" → Hệ thống tạo mã
3. Bật "Phát hiện gian lận"
   - ✅ Phát hiện đổi tab
   - ✅ Phát hiện đổi thiết bị
   - ✅ Khóa khi thoát (30 giây)
4. (Optional) Bật camera và ghi màn hình
5. Tạo đề → Thêm câu hỏi thủ công

### Tạo đề tự động
1. Nhập thông tin cơ bản
2. Bật checkbox "Tạo đề tự động"
3. Nhập phân bổ:
   - Nhận biết: 10, Thông hiểu: 15, Vận dụng: 5, VD cao: 3
   - Trắc nghiệm: 28, Tự luận: 5
4. Chọn chương 1, 2, 3
5. Click "Tạo đề thi"
6. Hệ thống tự động:
   - Chọn 33 câu từ ngân hàng
   - Sắp xếp theo order
   - Gán điểm (MC: 1đ, Essay: 2đ)
7. Redirect → Exam detail để review và chỉnh sửa

---

## Testing Checklist

### Security Features
- [ ] Access code generation works
- [ ] Access code validation (student side)
- [ ] Restrict to class check
- [ ] Tab switch detection
- [ ] Device change detection
- [ ] Lock on exit timer
- [ ] Camera permission request
- [ ] Screen recording start

### Auto-Generate Features
- [ ] Topics load when subject selected
- [ ] Questions selected by Bloom level
- [ ] Questions selected by type
- [ ] Questions filtered by topics
- [ ] No duplicate questions
- [ ] Correct order assignment
- [ ] Correct points assignment
- [ ] `is_auto_generated` flag saved
- [ ] `auto_gen_criteria` JSON saved

---

## Future Enhancements

1. **AI Proctoring**: Phát hiện gian lận qua camera bằng AI
2. **Browser Lockdown**: Khóa browser ở chế độ kiosk
3. **Biometric Auth**: Xác thực vân tay/khuôn mặt
4. **Live Monitoring Dashboard**: Giáo viên xem realtime học sinh làm bài
5. **Question Difficulty Balance**: Cân bằng độ khó tự động
6. **Adaptive Testing**: Câu hỏi thay đổi theo trình độ học sinh

---

## Notes

- Tất cả security features cần triển khai ở **student exam interface** (Phase 6)
- Auto-generate chỉ chọn từ **questions in_question_bank = true**
- Auto-generate chỉ chọn câu của **current teacher** (created_by)
- Access code có thể share qua ChatRoom tự động
- Camera/Screen recording cần HTTPS

---

## Migration Command

```bash
php artisan migrate
```

Migration file: `2025_11_17_142700_add_security_fields_to_exams_table.php`
