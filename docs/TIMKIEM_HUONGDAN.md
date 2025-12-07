# Tính năng Tìm kiếm Toàn cục

## Tổng quan
Tính năng tìm kiếm toàn cục cho phép người dùng tìm kiếm nội dung trên toàn hệ thống MegaLearning từ thanh tìm kiếm trên header.

## Cách sử dụng

### Đối với Người dùng

1. **Nhập từ khóa tìm kiếm** vào thanh tìm kiếm ở góc trên bên trái
2. **Chờ 0.3 giây** sau khi gõ xong, kết quả sẽ tự động hiển thị
3. **Xem kết quả** được phân loại theo:
   - 📝 Đề thi / Bài kiểm tra
   - 📚 Môn học / Khóa học
   - 📖 Chủ đề
   - 📄 Tài liệu
   - 💬 Câu hỏi diễn đàn
4. **Click vào kết quả** để xem chi tiết
5. **Nhấn phím Esc** hoặc click ra ngoài để đóng kết quả

### Đối với Giảng viên và Quản trị viên

Giảng viên và Quản trị viên có thể tìm kiếm tất cả các loại nội dung giống như sinh viên, nhưng kết quả sẽ dẫn đến trang quản lý phù hợp với vai trò của họ.

## Tính năng

### Tìm kiếm thông minh
- ✅ Tìm kiếm đồng thời trên 5 loại nội dung
- ✅ Tự động debounce để giảm tải server
- ✅ Hiển thị kết quả theo thời gian thực
- ✅ Phân loại kết quả rõ ràng
- ✅ Hiển thị thông tin chi tiết cho mỗi kết quả

### Bảo mật
- 🔒 Yêu cầu đăng nhập
- 🔒 Chỉ hiển thị nội dung đã được phê duyệt
- 🔒 URL phù hợp với vai trò người dùng
- 🔒 Bảo vệ chống SQL Injection và XSS

### Hiệu suất
- ⚡ Giới hạn tối đa 5 kết quả mỗi loại
- ⚡ Debounce 300ms giảm số lần gọi API
- ⚡ Eager loading tránh N+1 queries

## Loại nội dung có thể tìm kiếm

### 1. Đề thi / Bài kiểm tra
- Tìm theo: Tiêu đề, Mô tả
- Điều kiện: Chỉ đề thi đã xuất bản
- Hiển thị: Tiêu đề, Môn học

### 2. Môn học / Khóa học
- Tìm theo: Tên môn học, Mã môn học, Mô tả
- Điều kiện: Chỉ môn học đang hoạt động
- Hiển thị: Tên môn học, Mã môn học, Giảng viên

### 3. Chủ đề
- Tìm theo: Tên chủ đề, Mô tả
- Điều kiện: Không có
- Hiển thị: Tên chủ đề, Môn học

### 4. Tài liệu
- Tìm theo: Tiêu đề, Mô tả, Tên file
- Điều kiện: Chỉ tài liệu đã được phê duyệt
- Hiển thị: Tiêu đề, Môn học, Loại file

### 5. Câu hỏi Diễn đàn
- Tìm theo: Tiêu đề, Nội dung
- Điều kiện: Không có
- Hiển thị: Tiêu đề, Tác giả

## Kỹ thuật thực hiện

### Backend (Laravel)

**Controller**: `app/Http/Controllers/SearchController.php`
- Phương thức chính: `search(Request $request)`
- Trả về JSON với kết quả tìm kiếm

**Route**: Được định nghĩa trong `routes/web.php`
```php
Route::get('/search', [SearchController::class, 'search'])->name('search');
```

### Frontend (JavaScript)

**File**: `resources/views/layouts/partials/header.blade.php`
- Sử dụng Vanilla JavaScript (không cần thư viện)
- Debouncing để tối ưu hiệu suất
- Xử lý sự kiện: input, focus, blur, keydown, click
- Render kết quả động

## Cấu trúc API

### Request
```
GET /search?query={từ_khóa}
```

### Response thành công
```json
{
  "success": true,
  "results": {
    "exams": [...],
    "subjects": [...],
    "topics": [...],
    "documents": [...],
    "forum_questions": [...]
  },
  "total": 10,
  "query": "từ khóa tìm kiếm"
}
```

### Response lỗi
```json
{
  "success": false,
  "message": "Đã xảy ra lỗi khi tìm kiếm. Vui lòng thử lại.",
  "results": { ... },
  "total": 0
}
```

## Hướng dẫn Test

### 1. Test Cơ bản
```bash
# Đảm bảo server đã chạy
php artisan serve

# Truy cập ứng dụng và đăng nhập
# Nhập từ khóa vào thanh tìm kiếm
# Kiểm tra kết quả hiển thị
```

### 2. Test API trực tiếp
```bash
# Sử dụng curl hoặc Postman
curl -H "X-Requested-With: XMLHttpRequest" \
     -H "Accept: application/json" \
     "http://localhost:8000/search?query=test"
```

### 3. Checklist Test
- [ ] Tìm kiếm với từ khóa tiếng Việt có dấu
- [ ] Tìm kiếm với từ khóa tiếng Anh
- [ ] Tìm kiếm với ký tự đặc biệt
- [ ] Tìm kiếm với chuỗi rỗng
- [ ] Test với tài khoản Student
- [ ] Test với tài khoản Teacher
- [ ] Test với tài khoản Admin
- [ ] Test đóng dropdown bằng Esc
- [ ] Test đóng dropdown bằng click bên ngoài
- [ ] Test hiển thị loading spinner
- [ ] Test hiển thị "Không có kết quả"

## Tối ưu hóa Database

Để cải thiện hiệu suất tìm kiếm, nên tạo index cho các cột sau:

```sql
-- Exams table
CREATE INDEX idx_exams_title ON exams(title);
CREATE INDEX idx_exams_description ON exams(description(100));

-- Subjects table
CREATE INDEX idx_subjects_name ON subjects(name);
CREATE INDEX idx_subjects_code ON subjects(code);

-- Topics table
CREATE INDEX idx_topics_name ON topics(name);

-- Documents table
CREATE INDEX idx_documents_title ON documents(title);
CREATE INDEX idx_documents_file_name ON documents(file_name);

-- Forum Questions table
CREATE INDEX idx_forumquestions_title ON forumquestions(title);
```

## Mở rộng trong tương lai

Các tính năng có thể bổ sung:
1. 🔍 Full-text search với MySQL FULLTEXT hoặc Laravel Scout
2. 🎯 Bộ lọc tìm kiếm (theo ngày, loại, môn học...)
3. 📜 Lịch sử tìm kiếm
4. 💡 Gợi ý tự động (autocomplete)
5. 📊 Thống kê tìm kiếm phổ biến
6. 📄 Phân trang kết quả
7. ⌨️ Điều hướng bằng bàn phím
8. ✨ Highlight từ khóa trong kết quả
9. 🚀 Cache kết quả tìm kiếm phổ biến
10. 🔖 Bookmark kết quả tìm kiếm

## Troubleshooting

### Không tìm thấy kết quả
**Nguyên nhân**: 
- Database chưa có dữ liệu
- Nội dung chưa được phê duyệt/xuất bản
- Từ khóa không chính xác

**Giải pháp**:
- Kiểm tra dữ liệu trong database
- Đảm bảo nội dung có trạng thái phù hợp
- Thử từ khóa khác

### Dropdown không hiển thị
**Nguyên nhân**:
- Lỗi JavaScript
- Route chưa được cấu hình
- CSRF token không hợp lệ

**Giải pháp**:
- Mở Console trong trình duyệt để xem lỗi
- Kiểm tra file `routes/web.php`
- Xóa cache: `php artisan cache:clear`

### Tìm kiếm chậm
**Nguyên nhân**:
- Chưa có index trên database
- Database có quá nhiều dữ liệu
- Server quá tải

**Giải pháp**:
- Tạo index cho các cột tìm kiếm (xem phần Tối ưu hóa Database)
- Giảm số lượng kết quả trả về
- Implement caching

## Liên hệ

Nếu gặp vấn đề hoặc có đề xuất cải tiến, vui lòng tạo issue trên GitHub hoặc liên hệ team phát triển.

---

**Version**: 1.0.0  
**Last Updated**: December 2025  
**Maintainer**: MegaLearning Team
