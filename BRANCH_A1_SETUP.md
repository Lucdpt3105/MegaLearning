# Branch A1 - Setup Guide

## Tổng quan
Nhánh này đã hoàn thành các Phase sau:
- ✅ **Phase 1**: Subject Management (UC-GV-010 to UC-GV-015)
- ✅ **Phase 2**: Document Management (UC-GV-070 to UC-GV-074)
- ✅ **Phase 3**: Student Management (UC-GV-050 to UC-GV-054)
- ✅ **Chat System**: Class-based chat rooms (mỗi lớp có chat riêng)

---

## Database Migrations

### Migrations mới trong nhánh A1:

#### 1. User Profile Fields
**File**: `2025_11_16_151131_add_student_profile_fields_to_users_table.php`
- Thêm: `student_id`, `gender`, `date_of_birth`, `address`

#### 2. Chat Rooms - Class Room Integration
**File**: `2025_11_16_160207_add_class_room_id_to_chat_rooms_table.php`
- Thêm: `class_room_id` (foreign key to class_rooms)
- Mỗi lớp học có chat room riêng

#### 3. Chat Room Type Update
**File**: `2025_11_16_161453_update_room_type_enum_in_chat_rooms_table.php`
- Cập nhật ENUM: `room_type` ('group', 'private', 'subject', 'class')

### Migrations có sẵn cần thiết:
- `create_users_table.php`
- `create_subjects_table.php`
- `create_documents_table.php`
- `create_class_rooms_table.php`
- `create_class_enrollments_table.php`
- `create_chat_rooms_table.php`
- `create_chat_messages_table.php`
- `create_chat_room_members_table.php`
- `create_permission_tables.php` (Spatie)

---

## Seeders

### Thứ tự chạy Seeders:

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder  # Tạo roles: teacher, student, admin
php artisan db:seed --class=UserSeeder                 # Tạo users mẫu
php artisan db:seed --class=SubjectSeeder              # Tạo môn học mẫu
php artisan db:seed --class=ClassRoomSeeder            # Tạo lớp học mẫu
php artisan db:seed --class=StudentSeeder              # Gắn học sinh vào lớp
```

### Chi tiết Seeders:

#### RolesAndPermissionsSeeder
- Tạo 3 roles: `teacher`, `student`, `admin`
- Permissions cho từng role

#### UserSeeder
- Teacher: `ngocmai@example.com` / `password123`
- Students: Nhiều tài khoản học sinh

#### SubjectSeeder
- Tạo các môn học mẫu với teacher

#### ClassRoomSeeder
- Tạo lớp học cho mỗi môn
- Tự động tạo chat room cho mỗi lớp

#### StudentSeeder
- Gắn học sinh vào các lớp
- Tự động thêm vào chat room của lớp

---

## Setup Instructions

### 1. Clone và checkout nhánh
```bash
git checkout a1
```

### 2. Cài đặt dependencies
```bash
composer install
npm install
```

### 3. Environment
```bash
cp .env.example .env
php artisan key:generate
```

Cấu hình database trong `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=learning3
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Chạy migrations
```bash
php artisan migrate:fresh
```

### 5. Chạy seeders (theo thứ tự)
```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=SubjectSeeder
php artisan db:seed --class=ClassRoomSeeder
php artisan db:seed --class=StudentSeeder
```

Hoặc chạy tất cả:
```bash
php artisan migrate:fresh --seed
```

### 6. Storage link
```bash
php artisan storage:link
```

### 7. Build assets
```bash
npm run dev
```

Hoặc cho production:
```bash
npm run build
```

### 8. Start server
```bash
php artisan serve
```

---

## Login Credentials

### Teacher
- Email: `ngocmai@example.com`
- Password: `password123`

### Students
- Email: `student1@example.com` đến `student30@example.com`
- Password: `password123`

---

## Features Implemented

### Phase 1: Subject Management
- ✅ UC-GV-011: Thêm môn học mới
- ✅ UC-GV-012: Cập nhật thông tin môn học
- ✅ UC-GV-013: Xóa môn học
- ✅ UC-GV-014: Xem danh sách môn học

### Phase 2: Document Management
- ✅ UC-GV-071: Thêm tài liệu
- ✅ UC-GV-072: Sửa tài liệu
- ✅ UC-GV-073: Xóa tài liệu
- ✅ UC-GV-074: Xem danh sách tài liệu
- ✅ Folder management
- ✅ File upload/download
- ✅ Approval system

### Phase 3: Student Management
- ✅ UC-GV-050: Quản lý học sinh - Hiển thị danh sách lớp
- ✅ UC-GV-051: Thêm học sinh vào lớp
- ✅ UC-GV-052: Xóa học sinh khỏi lớp
- ✅ UC-GV-053: Cập nhật ghi chú học sinh
- ✅ UC-GV-054: Xem thông tin chi tiết học sinh
- ✅ Search & Filter students
- ✅ Full profile management (name, student_id, gender, DOB, avatar, phone, email, address)

### Chat System
- ✅ Class-based chat rooms (mỗi lớp có chat riêng)
- ✅ Tự động tạo chat room khi tạo lớp
- ✅ Auto-sync members khi thêm/xóa học sinh
- ✅ Chat management trong trang quản lý lớp
- ✅ Add/Remove chat members
- ✅ Toggle chat status (active/inactive)

### Profile Management
- ✅ My Profile page với đầy đủ thông tin
- ✅ Avatar upload
- ✅ Role-based UI (Teacher/Student)
- ✅ Vietnamese validation messages

---

## Models & Relationships

### User
- hasMany: classRoomsAsTeacher
- belongsToMany: classRooms (through enrollments)

### Subject
- belongsTo: teacher (User)
- hasMany: classRooms, documents, topics, exams

### ClassRoom
- belongsTo: subject, teacher
- hasMany: enrollments
- belongsToMany: students (through enrollments)
- hasOne: chatRoom

### ChatRoom
- belongsTo: classRoom, subject
- hasMany: messages
- belongsToMany: members (Users)

### ClassEnrollment
- belongsTo: classRoom, student (User)
- Fields: status (active/dropped), enrolled_at, dropped_at, notes

---

## Routes

### Teacher Routes
```php
// Subjects
GET    /teacher/subjects
POST   /teacher/subjects
GET    /teacher/subjects/{id}
PUT    /teacher/subjects/{id}
DELETE /teacher/subjects/{id}

// Documents
GET    /teacher/documents
POST   /teacher/documents
GET    /teacher/documents/{id}
PUT    /teacher/documents/{id}
DELETE /teacher/documents/{id}
GET    /teacher/documents/{id}/download

// Students
GET    /teacher/students
GET    /teacher/students/{classRoom}
POST   /teacher/students/{classRoom}/add
DELETE /teacher/students/{classRoom}/remove/{studentId}
PUT    /teacher/students/{classRoom}/notes/{studentId}
PUT    /teacher/students/{classRoom}/info/{studentId}

// Class Chat
POST   /teacher/students/{classRoom}/chat/members
DELETE /teacher/students/{classRoom}/chat/members/{userId}
POST   /teacher/students/{classRoom}/chat/toggle

// Profile
GET    /profile
PUT    /profile
```

---

## Important Notes

### Khi merge vào main:
1. ✅ Tất cả migrations đã tested
2. ✅ Seeders chạy đúng thứ tự
3. ✅ Chat system hoạt động với class-based rooms
4. ✅ Profile management hoàn chỉnh
5. ✅ Student management với search/filter
6. ✅ Auto-sync chat members khi thêm/xóa học sinh

### Breaking Changes:
- Chat room không còn theo subject, giờ theo class
- Mỗi lớp học có chat room riêng
- ENUM `room_type` đã thêm giá trị 'class'

### Database Changes:
```sql
-- users table
ALTER TABLE users ADD COLUMN student_id VARCHAR(50);
ALTER TABLE users ADD COLUMN gender ENUM('male', 'female', 'other');
ALTER TABLE users ADD COLUMN date_of_birth DATE;
ALTER TABLE users ADD COLUMN address TEXT;

-- chat_rooms table
ALTER TABLE chat_rooms ADD COLUMN class_room_id BIGINT UNSIGNED;
ALTER TABLE chat_rooms MODIFY COLUMN room_type ENUM('group', 'private', 'subject', 'class');
```

---

## Testing Checklist

- [x] Login as teacher
- [x] Create/Edit/Delete subjects
- [x] Upload/Download documents
- [x] View class list
- [x] Add students to class
- [x] Remove students from class
- [x] Search/Filter students
- [x] Edit student profile
- [x] Chat room auto-created for each class
- [x] Chat members sync with class enrollments
- [x] Add/Remove chat members manually
- [x] Toggle chat status
- [x] Edit profile with all fields
- [x] Avatar upload

---

## Version Info
- Branch: `a1`
- Laravel: 11.x
- PHP: 8.2+
- Node: 18+
- Database: MySQL 8.0+

---

**Ready to merge to main! 🚀**
