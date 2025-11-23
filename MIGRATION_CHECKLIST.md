# Branch A1 - Migration & Seeder Checklist

## ✅ Migrations Created (Thứ tự chạy)

### Core Tables
1. ✅ `create_users_table` - Bảng users cơ bản
2. ✅ `create_permission_tables` - Spatie permission (roles, permissions)
3. ✅ `create_subjects_table` - Môn học
4. ✅ `create_class_rooms_table` - Lớp học
5. ✅ `create_class_enrollments_table` - Đăng ký lớp học
6. ✅ `create_documents_table` - Tài liệu
7. ✅ `create_chat_rooms_table` - Chat rooms
8. ✅ `create_chat_messages_table` - Chat messages
9. ✅ `create_chat_room_members_table` - Chat members

### New Migrations (Branch A1)
10. ✅ `2025_11_16_151131_add_student_profile_fields_to_users_table`
    - Thêm: student_id, gender, date_of_birth, address
    
11. ✅ `2025_11_16_160207_add_class_room_id_to_chat_rooms_table`
    - Thêm: class_room_id với foreign key
    
12. ✅ `2025_11_16_161453_update_room_type_enum_in_chat_rooms_table`
    - Cập nhật ENUM room_type thêm 'class'

---

## ✅ Seeders Created (Thứ tự chạy)

1. ✅ **RolesAndPermissionsSeeder**
   - Tạo roles: teacher, student, admin
   - Tạo permissions cơ bản
   
2. ✅ **UserSeeder**
   - Teacher: ngocmai@example.com
   - Students: student1@example.com đến student30@example.com
   - Tất cả password: password123
   
3. ✅ **SubjectSeeder**
   - Tạo 9 môn học mẫu
   - Gắn teacher cho mỗi môn
   
4. ✅ **ClassRoomSeeder**
   - Tạo 2-3 lớp cho mỗi môn học
   - Total: ~18-27 lớp
   - Tự động tạo chat room cho mỗi lớp (room_type = 'class')
   
5. ✅ **StudentSeeder**
   - Gắn học sinh vào các lớp
   - Tự động thêm vào chat room của lớp
   - Random 10-25 học sinh/lớp

---

## 📋 Pre-Merge Checklist

### Database
- [x] Tất cả migrations chạy thành công
- [x] Foreign keys đúng
- [x] ENUM values đầy đủ ('class' added)
- [x] Indexes đã tạo
- [x] Seeders chạy đúng thứ tự

### Models
- [x] User model: fillable updated
- [x] ChatRoom model: class_room_id added, classRoom() relationship
- [x] ClassRoom model: chatRoom() relationship
- [x] Relationships tested

### Controllers
- [x] SubjectController: removed chat management
- [x] StudentController: 
  - show() auto-creates chat room
  - addStudents() syncs chat members
  - removeStudent() removes from chat
  - addChatMember(), removeChatMember(), toggleChatStatus()

### Views
- [x] subjects/show.blade.php: chat section removed
- [x] students/show.blade.php: 
  - Tab system (Students + Chat)
  - Chat management integrated
  - Routes updated to students.chat.*

### Routes
- [x] Removed: teacher.subjects.chat-room.*
- [x] Added: teacher.students.chat.* (3 routes)
- [x] All routes tested

### Features
- [x] Mỗi lớp có chat room riêng
- [x] Auto-create chat khi vào quản lý lớp
- [x] Auto-sync members khi add/remove students
- [x] Chat management in class page
- [x] Profile management với đầy đủ fields

---

## 🔧 Manual Tests Before Merge

### Teacher Flow
1. [ ] Login as teacher
2. [ ] Tạo môn học mới
3. [ ] Tạo lớp học cho môn
4. [ ] Vào quản lý lớp → Chat room tự động tạo
5. [ ] Thêm học sinh → Check chat members updated
6. [ ] Xóa học sinh → Check chat members removed
7. [ ] Add chat member manually
8. [ ] Remove chat member
9. [ ] Toggle chat status
10. [ ] Edit profile with all fields

### Student Flow
1. [ ] Login as student
2. [ ] View classes enrolled
3. [ ] Edit profile
4. [ ] Upload avatar

### Database Verification
```sql
-- Check chat rooms have class_room_id
SELECT * FROM chat_rooms WHERE room_type = 'class';

-- Check ENUM values
SHOW COLUMNS FROM chat_rooms LIKE 'room_type';

-- Check users profile fields
DESCRIBE users;

-- Check chat members sync
SELECT cr.room_name, COUNT(crm.user_id) as members_count
FROM chat_rooms cr
LEFT JOIN chat_room_members crm ON cr.id = crm.room_id
WHERE cr.room_type = 'class'
GROUP BY cr.id;
```

---

## 📦 Files to Commit

### New Files
- [x] BRANCH_A1_SETUP.md
- [x] MIGRATION_CHECKLIST.md
- [x] setup-branch-a1.bat
- [x] setup-branch-a1.sh

### Modified Files
- [x] app/Models/User.php
- [x] app/Models/ChatRoom.php
- [x] app/Models/ClassRoom.php
- [x] app/Http/Controllers/Teacher/StudentController.php
- [x] app/Http/Controllers/Teacher/SubjectController.php
- [x] app/Http/Controllers/ProfileController.php
- [x] resources/views/teacher/subjects/show.blade.php
- [x] resources/views/teacher/students/show.blade.php
- [x] resources/views/profile/edit.blade.php
- [x] routes/web.php

### Migration Files (New)
- [x] 2025_11_16_151131_add_student_profile_fields_to_users_table.php
- [x] 2025_11_16_160207_add_class_room_id_to_chat_rooms_table.php
- [x] 2025_11_16_161453_update_room_type_enum_in_chat_rooms_table.php

---

## 🚀 Merge Instructions

### 1. Review Changes
```bash
git status
git diff main
```

### 2. Run Tests
```bash
php artisan test
```

### 3. Create Pull Request
```bash
git checkout main
git pull origin main
git checkout a1
git rebase main  # Resolve conflicts if any
```

### 4. Merge
```bash
git checkout main
git merge a1
git push origin main
```

### 5. Production Deployment
```bash
# On production server
git pull origin main
composer install --no-dev
npm run build
php artisan migrate
php artisan db:seed --class=RolesAndPermissionsSeeder  # If needed
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

---

## ⚠️ Important Notes

1. **Breaking Change**: Chat system changed from subject-based to class-based
2. **Database**: Run all 3 new migrations in order
3. **Seeders**: ClassRoomSeeder now creates chat rooms automatically
4. **Backward Compatibility**: Old subject chat rooms will still exist but not used

---

## 📞 Support

If issues after merge:
1. Check migration order
2. Verify ENUM values: `SHOW COLUMNS FROM chat_rooms LIKE 'room_type';`
3. Re-run seeders if needed
4. Clear all caches

**Branch A1 is ready for merge to main! ✅**
