# 🎲 Database Seeding Guide

## 📋 Tổng quan

Dự án MegaLearning đã có **hệ thống seeder hoàn chỉnh** để tạo dữ liệu mẫu phong phú cho việc testing và demo.

## 🚀 Cách sử dụng

### Option 1: Sử dụng Script (Khuyến nghị) ⭐

**Windows:**
```bash
# Chạy full seeding (xóa toàn bộ + tạo mới)
.\scripts\seed-database.bat

# Hoặc chỉ thêm dữ liệu (không xóa)
.\scripts\quick-seed.bat
```

**Linux/Mac:**
```bash
# Full seeding
php artisan migrate:fresh --seed

# Hoặc chỉ seed thêm
php artisan db:seed
```

### Option 2: Dòng lệnh thủ công

```bash
# 1. Xóa toàn bộ và tạo lại database
php artisan migrate:fresh

# 2. Chạy seeder
php artisan db:seed

# Hoặc kết hợp 1 lệnh:
php artisan migrate:fresh --seed
```

## 📦 Dữ liệu được tạo

Sau khi chạy seeder, database sẽ có:

### 👥 Users & Authentication
- ✅ **3 Roles**: Admin, Teacher, Student
- ✅ **Permissions**: Full CRUD permissions
- ✅ **Demo Users**:
  ```
  Admin:   admin@megalearning.com    / password
  Teacher: teacher@megalearning.com  / password  
  Student: student@megalearning.com  / password
  ```
- ✅ **Additional Students**: 5-10 học sinh khác

### 📚 Educational Content
- ✅ **5 Subjects**:
  - Toán học (MATH101)
  - Vật lý (PHYS101)
  - Hóa học (CHEM101)
  - Lập trình Web (WEB101)
  - Cơ sở dữ liệu (DB101)

- ✅ **25+ Topics**: Phân bổ theo môn học
- ✅ **60+ Questions**: 
  - Math: 20 questions
  - Physics: 20 questions
  - Chemistry: 16 questions
  - Web: Có thể thêm
  - Database: Có thể thêm
  
- ✅ **Multiple Choice Answers**: Mỗi câu hỏi có 4 đáp án

- ✅ **20+ Documents**:
  - PDFs (giáo trình, bài tập)
  - PowerPoint slides
  - Word documents
  - Source code (zip)
  - Video links

### 🏫 Classes & Enrollments
- ✅ **ClassRooms**: Lớp học với teacher và students
- ✅ **Enrollments**: Học sinh đã đăng ký
- ✅ **Attendance**: Điểm danh (nếu có)

### 📝 Exams & Assessments
- ✅ **Sample Exams**: Đề thi mẫu
- ✅ **Submissions**: Bài làm của học sinh (có thể)
- ✅ **Grades**: Điểm số

### 💬 Communication
- ✅ **Private Chat Rooms**: Phòng chat riêng
- ✅ **Chat Messages**: Tin nhắn mẫu
- ✅ **Forum Questions**: Câu hỏi thảo luận
- ✅ **Forum Answers**: Câu trả lời
- ✅ **Votes**: Upvote/Downvote

## 🎯 Seeders có sẵn

### Core Seeders (Đã được gọi trong DatabaseSeeder)

1. **RolesAndPermissionsSeeder** - Roles & Permissions
2. **UserSeeder** - Admin, Teacher, Student users
3. **SubjectSeeder** - 5 môn học
4. **TopicSeeder** - Topics cho mỗi môn
5. **QuestionBankSeeder** - 60+ câu hỏi
6. **DocumentSeeder** - 20+ tài liệu mẫu ⭐ MỚI
7. **StudentSeeder** - Thêm học sinh
8. **ClassRoomSeeder** - Lớp học + enrollments
9. **ExamSeeder** - Đề thi
10. **PrivateChatSeeder** - Chat riêng
11. **ChatSeeder** - Messages
12. **ForumSeeder** - Diễn đàn

### Chạy seeder riêng lẻ

```bash
# Chỉ chạy một seeder cụ thể
php artisan db:seed --class=SubjectSeeder
php artisan db:seed --class=QuestionBankSeeder
php artisan db:seed --class=DocumentSeeder
```

## ⚠️ Lưu ý quan trọng

### 1. **Documents là fake file paths**
- Các documents được tạo có **fake file paths** (không có file thật)
- Path format: `documents/{subject_id}/{filename}`
- Để test download thật, cần upload file thật qua UI

### 2. **Migration Fresh = Xóa toàn bộ**
```bash
php artisan migrate:fresh --seed  # ⚠️ XÓA TOÀN BỘ DATA!
```

### 3. **Seed thêm data (không xóa)**
```bash
php artisan db:seed  # ✅ Chỉ thêm, không xóa
```

### 4. **Kiểm tra trước khi seed**
```bash
# Xem danh sách migrations
php artisan migrate:status

# Test database connection
php artisan db:show
```

## 🔧 Tùy chỉnh Seeders

### Thêm dữ liệu riêng

Sửa file `database/seeders/DatabaseSeeder.php`:

```php
public function run(): void
{
    $this->call([
        RolesAndPermissionsSeeder::class,
        UserSeeder::class,
        SubjectSeeder::class,
        // ... existing seeders
        
        // Thêm seeder của bạn ở đây
        MyCustomSeeder::class,
    ]);
}
```

### Tạo seeder mới

```bash
php artisan make:seeder VideoCallSeeder
```

Sau đó thêm vào `DatabaseSeeder.php`

## 📊 Kiểm tra dữ liệu sau khi seed

### Via Artisan Tinker
```bash
php artisan tinker

# Check users
>>> User::count()
=> 8

# Check subjects
>>> Subject::with('topics')->get()

# Check questions
>>> Question::count()
=> 60+

# Check documents
>>> Document::count()
=> 20+
```

### Via Database Client
```sql
SELECT * FROM users WHERE email LIKE '%@megalearning.com';
SELECT s.name, COUNT(t.id) as topics_count FROM subjects s LEFT JOIN topics t ON s.id = t.subject_id GROUP BY s.id;
SELECT COUNT(*) FROM questions;
SELECT COUNT(*) FROM documents;
```

## 🎨 Tips & Best Practices

### 1. Development workflow
```bash
# Mỗi lần thay đổi schema:
php artisan migrate:fresh --seed
```

### 2. Production deployment
```bash
# KHÔNG dùng migrate:fresh trên production!
php artisan migrate
# Seed data production riêng nếu cần
```

### 3. Testing
```bash
# Tạo database riêng cho test
DB_DATABASE=megalearning_test

# Chạy test với database riêng
php artisan test --env=testing
```

## 🆘 Troubleshooting

### Lỗi: "Class not found"
```bash
composer dump-autoload
php artisan db:seed
```

### Lỗi: "SQLSTATE[42000]"
- Kiểm tra `.env` database config
- Đảm bảo database đã được tạo
- Check MySQL service đang chạy

### Lỗi: "Call to undefined method"
```bash
composer install
php artisan optimize:clear
```

### Seed chậm
- Comment bớt seeders không cần thiết
- Giảm số lượng records trong seeder
- Sử dụng `DB::table()->insert()` thay vì Model::create()

## 📚 Tài liệu tham khảo

- [Laravel Seeding Documentation](https://laravel.com/docs/10.x/seeding)
- [Database Testing](https://laravel.com/docs/10.x/database-testing)
- [Faker Library](https://fakerphp.github.io/)

---

**Tạo bởi**: MegaLearning Team  
**Cập nhật**: December 2025
