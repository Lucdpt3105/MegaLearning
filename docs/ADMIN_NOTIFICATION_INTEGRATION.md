# Hướng dẫn tích hợp Thông báo tự động cho Admin

## 📋 Tổng quan

Service `AdminNotificationService` đã được tạo để gửi thông báo tự động cho admin khi có các hoạt động quan trọng từ học sinh và giáo viên.

## 🎯 Các loại thông báo được hỗ trợ

1. **Đăng ký người dùng mới** (học sinh/giáo viên)
2. **Bài nộp mới** (học sinh nộp bài thi)
3. **Lớp học mới** (giáo viên tạo lớp)
4. **Bài thi mới** (giáo viên tạo đề thi)
5. **Tham gia lớp** (học sinh enroll vào lớp)
6. **Câu hỏi diễn đàn** (có câu hỏi mới)
7. **Tài liệu mới** (upload file mới)
8. **Cảnh báo hệ thống** (thông báo quan trọng)

## 💻 Cách sử dụng

### 1. Inject service vào Controller

```php
use App\Services\AdminNotificationService;

class YourController extends Controller
{
    protected $adminNotificationService;

    public function __construct(AdminNotificationService $adminNotificationService)
    {
        $this->adminNotificationService = $adminNotificationService;
    }
}
```

### 2. Ví dụ tích hợp vào AuthController (Đăng ký)

**File:** `app/Http/Controllers/AuthController.php`

```php
use App\Services\AdminNotificationService;

public function __construct(AdminNotificationService $adminNotificationService)
{
    $this->adminNotificationService = $adminNotificationService;
}

public function register(Request $request)
{
    // ... validation code ...

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    $user->assignRole($validated['role']);

    // GỬI THÔNG BÁO CHO ADMIN
    if ($validated['role'] === 'student') {
        $this->adminNotificationService->notifyNewStudentRegistration($user);
    } elseif ($validated['role'] === 'teacher') {
        $this->adminNotificationService->notifyNewTeacherRegistration($user);
    }

    // ... rest of code ...
}
```

### 3. Ví dụ tích hợp vào ExamSubmission (Nộp bài)

**File:** `app/Http/Controllers/Student/ExamController.php`

```php
use App\Services\AdminNotificationService;

protected $adminNotificationService;

public function __construct(AdminNotificationService $adminNotificationService)
{
    $this->adminNotificationService = $adminNotificationService;
}

public function submitExam(Request $request, $examId)
{
    // ... validation and submit logic ...

    $submission = ExamSubmission::create([...]);

    // GỬI THÔNG BÁO CHO ADMIN
    $exam = Exam::find($examId);
    $student = auth()->user();
    $this->adminNotificationService->notifyNewExamSubmission($submission, $exam, $student);

    return redirect()->back()->with('success', 'Nộp bài thành công!');
}
```

### 4. Ví dụ tích hợp vào ClassRoom (Tạo lớp mới)

**File:** `app/Http/Controllers/Teacher/ClassRoomController.php`

```php
use App\Services\AdminNotificationService;

protected $adminNotificationService;

public function __construct(AdminNotificationService $adminNotificationService)
{
    $this->adminNotificationService = $adminNotificationService;
}

public function store(Request $request)
{
    // ... validation ...

    $classRoom = ClassRoom::create([...]);

    // GỬI THÔNG BÁO CHO ADMIN
    $teacher = auth()->user();
    $this->adminNotificationService->notifyNewClassCreated($classRoom, $teacher);

    return redirect()->route('teacher.classes.index')
        ->with('success', 'Tạo lớp học thành công!');
}
```

### 5. Ví dụ tích hợp vào Exam (Tạo bài thi)

**File:** `app/Http/Controllers/Teacher/ExamController.php`

```php
public function store(Request $request)
{
    // ... validation ...

    $exam = Exam::create([...]);

    // GỬI THÔNG BÁO CHO ADMIN
    $teacher = auth()->user();
    $classRoom = ClassRoom::find($exam->class_room_id);
    $this->adminNotificationService->notifyNewExamCreated($exam, $teacher, $classRoom);

    return redirect()->route('teacher.exams.index')
        ->with('success', 'Tạo bài thi thành công!');
}
```

### 6. Ví dụ tích hợp vào Enrollment (Tham gia lớp)

**File:** `app/Http/Controllers/Student/ClassRoomController.php`

```php
public function enroll($classId)
{
    $classRoom = ClassRoom::findOrFail($classId);
    $student = auth()->user();

    $enrollment = ClassEnrollment::create([
        'class_room_id' => $classId,
        'user_id' => $student->id,
        'status' => 'active',
    ]);

    // GỬI THÔNG BÁO CHO ADMIN
    app(AdminNotificationService::class)
        ->notifyStudentEnrolled($enrollment, $student, $classRoom);

    return redirect()->back()->with('success', 'Tham gia lớp thành công!');
}
```

### 7. Ví dụ tích hợp vào FileController (Upload tài liệu)

**File:** `app/Http/Controllers/Admin/FileController.php`

```php
use App\Services\AdminNotificationService;

public function store(Request $request)
{
    // ... validation and upload logic ...

    $document = Document::create([...]);

    // GỬI THÔNG BÁO CHO ADMIN (nếu không phải admin upload)
    if (!auth()->user()->hasRole('admin')) {
        app(AdminNotificationService::class)
            ->notifyNewDocumentUploaded($document, auth()->user());
    }

    return redirect()->route('admin.files.index')
        ->with('success', 'Upload thành công!');
}
```

## 🧪 Test thông báo

### Tạo test notification từ tinker:

```bash
php artisan tinker
```

```php
$service = app(\App\Services\AdminNotificationService::class);

// Test thông báo chung
$service->notifyAdmins(
    '🧪 Test Notification',
    'Đây là thông báo test từ tinker',
    'general',
    '/admin/dashboard'
);

// Test thông báo học sinh mới
$student = \App\Models\User::role('student')->first();
$service->notifyNewStudentRegistration($student);
```

### Hoặc tạo route test:

**File:** `routes/web.php`

```php
// TEST ROUTE (CHỈ DÙNG KHI DEVELOP)
Route::get('/test-admin-notification', function() {
    $service = app(\App\Services\AdminNotificationService::class);
    
    $count = $service->notifyAdmins(
        '🧪 Test Thông báo',
        'Đây là thông báo test',
        'general',
        route('admin.dashboard')
    );
    
    return "Đã gửi {$count} thông báo cho admin!";
})->middleware('auth');
```

Truy cập: `http://your-domain.com/test-admin-notification`

## 📊 Kiểm tra thông báo đã gửi

### Qua Database:

```sql
SELECT * FROM notifications 
WHERE notifiable_type = 'App\\Models\\User'
AND notifiable_id IN (SELECT id FROM users WHERE role = 'admin')
ORDER BY created_at DESC
LIMIT 10;
```

### Qua Laravel Tinker:

```bash
php artisan tinker
```

```php
// Lấy admin đầu tiên
$admin = \App\Models\User::role('admin')->first();

// Xem tất cả thông báo
$admin->notifications()->get();

// Xem thông báo chưa đọc
$admin->notifications()->whereNull('read_at')->get();

// Đếm thông báo
$admin->notifications()->count();
```

## 🎨 Customize thông báo

Bạn có thể thêm các method mới vào `AdminNotificationService.php`:

```php
/**
 * Thông báo custom của bạn
 */
public function notifyYourCustomEvent($data)
{
    return $this->notifyAdmins(
        '🎯 Tiêu đề custom',
        'Nội dung custom message',
        'custom_type',
        route('admin.your.route'),
        [
            'custom_field_1' => $data->field1,
            'custom_field_2' => $data->field2,
        ]
    );
}
```

## 🔔 Các loại type thông báo

- `general` - Thông báo chung
- `user_registration` - Đăng ký người dùng mới
- `exam_submission` - Nộp bài thi
- `exam_created` - Tạo bài thi mới
- `class_created` - Tạo lớp học mới
- `student_enrolled` - Tham gia lớp
- `forum_question` - Câu hỏi diễn đàn
- `document_uploaded` - Upload tài liệu
- `system_alert` - Cảnh báo hệ thống

## 🚀 Tự động hóa hoàn toàn với Events & Listeners

Để tự động hơn, bạn có thể tạo Events và Listeners:

```bash
php artisan make:event StudentRegistered
php artisan make:listener NotifyAdminOfNewStudent
```

Sau đó đăng ký trong `EventServiceProvider.php` để tự động trigger khi có sự kiện.

## ✅ Checklist triển khai

- [ ] Tạo `AdminNotificationService.php` (Đã có)
- [ ] Inject service vào các Controller cần thiết
- [ ] Thêm notification call sau các action quan trọng
- [ ] Test thông báo hoạt động
- [ ] Kiểm tra admin nhận được thông báo
- [ ] Customize UI/UX nếu cần
- [ ] Deploy lên production

## 📝 Lưu ý

- Thông báo được lưu vào database table `notifications`
- Admin có thể xem thông báo ở `/notifications`
- Icon chuông sẽ hiển thị số thông báo chưa đọc
- Thông báo có thể được đánh dấu đã đọc
- Hỗ trợ deep link (click vào thông báo sẽ đi đến trang liên quan)
