# Statistics & Rankings - Production Files

## Files Production (Giữ lại)

### 1. Database
- `database/migrations/2025_12_14_220918_create_exam_submission_answers_table.php`
  - Bảng pivot lưu câu trả lời của học sinh
  
- `database/seeders/ExamSubmissionSeeder.php`
  - Seeder sinh dữ liệu bài thi với điểm số thực tế
  - Distribution: 9-10 (15%), 8-9 (26%), 7-8 (18%), 6-7 (23%), 5-6 (7%), 0-5 (10%)

### 2. Backend
- `app/Http/Controllers/Admin/StatisticsController.php`
  - Controller xử lý tất cả trang thống kê
  - Methods: index(), studentStatistics(), rankings(), activityLogs(), usageDuration(), participation(), recalculateRankings()

- `app/Services/RankingService.php`
  - Service tính toán GPA và xếp hạng học sinh
  - calculateAllRankings(), calculateClassRoomRanking(), calculateStudentMetrics()

- `app/Console/Commands/CalculateStudentRankings.php`
  - Command tính rankings: `php artisan rankings:calculate`
  - Hỗ trợ options: --class_room_id, --student_id

### 3. Frontend Views
- `resources/views/admin/statistics/index.blade.php` - Dashboard
- `resources/views/admin/statistics/student-statistics.blade.php` - Thống kê học sinh
- `resources/views/admin/statistics/rankings.blade.php` - Xếp hạng
- `resources/views/admin/statistics/participation.blade.php` - Tham gia
- `resources/views/admin/statistics/activity-logs.blade.php` - Log hoạt động
- `resources/views/admin/statistics/usage-duration.blade.php` - Thời gian sử dụng

### 4. Utility Scripts (Giữ lại)
- `scripts/check-statistics.bat` - Kiểm tra hệ thống thống kê
- `scripts/open-statistics-pages.bat` - Mở trang trong browser
- `scripts/seed-statistics-data.bat` - Seed dữ liệu thống kê

### 5. Routes
- `routes/web.php` - Các routes cho statistics:
  - GET /admin/statistics
  - GET /admin/statistics/students
  - GET /admin/statistics/rankings
  - POST /admin/statistics/rankings/recalculate
  - GET /admin/statistics/activity-logs
  - GET /admin/statistics/usage-duration
  - GET /admin/statistics/participation

## Cách sử dụng

### Seed dữ liệu:
```bash
php artisan db:seed --class=ExamSubmissionSeeder
php artisan rankings:calculate
```

### Hoặc dùng script:
```bash
.\scripts\seed-statistics-data.bat
```

### Truy cập:
- http://127.0.0.1:8000/admin/statistics

## Database Status
- ✅ 23 students
- ✅ 32 rankings
- ✅ 77 graded submissions
- ✅ Data lấy từ DB thật (learning3)
