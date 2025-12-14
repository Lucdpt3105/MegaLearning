<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ForumQuestionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Auth\ForgotPasswordController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot Password Routes (UC-GLOBAL-003)
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

// Global Search Routes (UC-GLOBAL-004)
Route::middleware(['auth'])->group(function () {
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
    
    // Notifications Routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// Profile Routes (Manage Profile)
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('update');
    Route::get('/password', [ProfileController::class, 'editPassword'])->name('password');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/avatar', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
});

Route::get('/', function () {
    return view('welcome');
});

// Chat route - Requires authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    
    // Chat API endpoints (use web session)
    Route::prefix('api/v1/chat')->group(function () {
        Route::get('current-user', [ChatController::class, 'getCurrentUser']);
        Route::get('rooms', [ChatController::class, 'getRooms']);
        Route::get('rooms/{roomId}/messages', [ChatController::class, 'getMessages']);
        Route::get('users', [ChatController::class, 'getUsers']);
        Route::post('rooms', [ChatController::class, 'store']);
        Route::post('rooms/{roomId}/messages', [ChatController::class, 'sendMessage']);
        Route::post('upload', [ChatController::class, 'uploadFile']);
        Route::post('rooms/{roomId}/mark-read', [ChatController::class, 'markAsRead']);
        Route::get('unread-count', [ChatController::class, 'getTotalUnreadCount']);
        Route::post('rooms/{roomId}/members', [ChatController::class, 'addMember']);
        Route::delete('rooms/{roomId}/members/{userId}', [ChatController::class, 'removeMember']);
        Route::put('rooms/{roomId}', [ChatController::class, 'update']);
        Route::delete('rooms/{roomId}', [ChatController::class, 'destroy']);
        Route::post('rooms/private', [ChatController::class, 'createPrivateRoom']);
        Route::post('set-user', [ChatController::class, 'setUser']); // Legacy
    });
});

// Universal Dashboard Route (redirects based on role)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('teacher')) {
            return redirect()->route('teacher.dashboard');
        } elseif ($user->hasRole('student')) {
            return redirect()->route('student.dashboard');
        }
        
        // Default fallback
        return view('dashboard.index');
    })->name('dashboard');
});

// Student Dashboard (authenticated students)
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'welcome'])->name('dashboard');
    Route::get('/welcome', [StudentController::class, 'welcome'])->name('welcome'); // Keep for backward compatibility
    
    // Courses Management (UC-STUDENT-001 to UC-STUDENT-006)
    Route::get('/courses', [App\Http\Controllers\Student\CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/browse', [App\Http\Controllers\Student\CourseController::class, 'browse'])->name('courses.browse');
    Route::get('/courses/{id}', [App\Http\Controllers\Student\CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{id}/materials', [App\Http\Controllers\Student\CourseController::class, 'materials'])->name('courses.materials');
    Route::get('/courses/{id}/schedule', [App\Http\Controllers\Student\CourseController::class, 'schedule'])->name('courses.schedule');
    Route::post('/courses/{id}/enroll', [App\Http\Controllers\Student\CourseController::class, 'enroll'])->name('courses.enroll');
    
    // Documents Download
    Route::get('/documents/{documentId}/download', [App\Http\Controllers\Student\CourseController::class, 'downloadDocument'])->name('documents.download');
    
    // Video Calls Management
    Route::get('/video-calls', [App\Http\Controllers\Student\VideoCallController::class, 'index'])->name('video-calls.index');
    Route::get('/video-calls/{id}', [App\Http\Controllers\Student\VideoCallController::class, 'show'])->name('video-calls.show');
    Route::get('/video-calls/{id}/join', [App\Http\Controllers\Student\VideoCallController::class, 'join'])->name('video-calls.join');
    Route::post('/video-calls/{id}/leave', [App\Http\Controllers\Student\VideoCallController::class, 'leave'])->name('video-calls.leave');
    
    // Exams Management
    Route::get('/exams', [App\Http\Controllers\Student\ExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/{id}', [App\Http\Controllers\Student\ExamController::class, 'show'])->name('exams.show');
    Route::match(['get', 'post'], '/exams/{id}/take', [App\Http\Controllers\Student\ExamController::class, 'take'])->name('exams.take');
    Route::post('/exams/{id}/submit', [App\Http\Controllers\Student\ExamController::class, 'submit'])->name('exams.submit');
    Route::get('/exams/result/{submissionId}', [App\Http\Controllers\Student\ExamController::class, 'result'])->name('exams.result');
    
    // Grades Management
    Route::get('/grades', [App\Http\Controllers\Student\GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/{submissionId}', [App\Http\Controllers\Student\GradeController::class, 'show'])->name('grades.show');
});

// Teacher Routes
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    
    // Phase 1: Subject Management (UC-GV-010 to UC-GV-015)
    Route::resource('subjects', App\Http\Controllers\Teacher\SubjectController::class);
    Route::post('/subjects/{subject}/chat-room', [App\Http\Controllers\Teacher\SubjectController::class, 'createChatRoom'])->name('subjects.chat-room.create');
    Route::get('/subjects/{subject}/chat-room', [App\Http\Controllers\Teacher\SubjectController::class, 'manageChatRoom'])->name('subjects.chat-room.manage');
    Route::post('/subjects/{subject}/chat-room/members', [App\Http\Controllers\Teacher\SubjectController::class, 'addChatMember'])->name('subjects.chat-room.add-member');
    Route::delete('/subjects/{subject}/chat-room/members/{userId}', [App\Http\Controllers\Teacher\SubjectController::class, 'removeChatMember'])->name('subjects.chat-room.remove-member');
    Route::post('/subjects/{subject}/chat-room/toggle', [App\Http\Controllers\Teacher\SubjectController::class, 'toggleChatStatus'])->name('subjects.chat-room.toggle');
    
    // Topic Management (UC-GV-016 to UC-GV-019)
    Route::resource('topics', App\Http\Controllers\Teacher\TopicController::class);
    Route::post('/topics/reorder', [App\Http\Controllers\Teacher\TopicController::class, 'reorder'])->name('topics.reorder');
    Route::post('/topics/bulk-delete', [App\Http\Controllers\Teacher\TopicController::class, 'bulkDelete'])->name('topics.bulk-delete');
    
    // Phase 2: Document Management (UC-GV-070 to UC-GV-074)
    Route::resource('documents', App\Http\Controllers\Teacher\DocumentController::class);
    Route::post('/documents/folder', [App\Http\Controllers\Teacher\DocumentController::class, 'createFolder'])->name('documents.folder.create');
    Route::post('/documents/{document}/move', [App\Http\Controllers\Teacher\DocumentController::class, 'moveToFolder'])->name('documents.move');
    Route::get('/documents/{document}/download', [App\Http\Controllers\Teacher\DocumentController::class, 'download'])->name('documents.download');
    Route::post('/documents/{document}/approve', [App\Http\Controllers\Teacher\DocumentController::class, 'approve'])->name('documents.approve');
    Route::post('/documents/{document}/reject', [App\Http\Controllers\Teacher\DocumentController::class, 'reject'])->name('documents.reject');
    
    // Phase 3: Student Management (UC-GV-050 to UC-GV-054)
    Route::get('/students', [App\Http\Controllers\Teacher\StudentController::class, 'index'])->name('students');
    Route::get('/students/{classRoom}', [App\Http\Controllers\Teacher\StudentController::class, 'show'])->name('students.show');
    Route::post('/students/{classRoom}/add', [App\Http\Controllers\Teacher\StudentController::class, 'addStudents'])->name('students.add');
    Route::delete('/students/{classRoom}/remove/{studentId}', [App\Http\Controllers\Teacher\StudentController::class, 'removeStudent'])->name('students.remove');
    
    // Class Chat Room Management
    Route::post('/students/{classRoom}/chat/members', [App\Http\Controllers\Teacher\StudentController::class, 'addChatMember'])->name('students.chat.add-member');
    Route::delete('/students/{classRoom}/chat/members/{userId}', [App\Http\Controllers\Teacher\StudentController::class, 'removeChatMember'])->name('students.chat.remove-member');
    Route::post('/students/{classRoom}/chat/toggle', [App\Http\Controllers\Teacher\StudentController::class, 'toggleChatStatus'])->name('students.chat.toggle');
    Route::put('/students/{classRoom}/notes/{studentId}', [App\Http\Controllers\Teacher\StudentController::class, 'updateNotes'])->name('students.update-notes');
    Route::put('/students/{classRoom}/enrollment/{studentId}', [App\Http\Controllers\Teacher\StudentController::class, 'updateEnrollment'])->name('students.update-enrollment');
    Route::put('/students/{classRoom}/info/{studentId}', [App\Http\Controllers\Teacher\StudentController::class, 'updateStudentInfo'])->name('students.update-info');
    
    // Phase 4: Question Bank Management
    Route::get('/questions/subjects/{subject}', [App\Http\Controllers\Teacher\QuestionController::class, 'bySubject'])->name('questions.by-subject');
    Route::get('/questions/export/{subject}', [App\Http\Controllers\Teacher\QuestionController::class, 'export'])->name('questions.export');
    Route::post('/questions/import/{subject}', [App\Http\Controllers\Teacher\QuestionController::class, 'import'])->name('questions.import');
    Route::get('/questions/download-template', [App\Http\Controllers\Teacher\QuestionController::class, 'downloadTemplate'])->name('questions.download-template');
    Route::resource('questions', App\Http\Controllers\Teacher\QuestionController::class);
    
    // Subjects API
    Route::get('/subjects/{subject}/topics', [App\Http\Controllers\Teacher\SubjectController::class, 'getTopics'])->name('subjects.topics');
    
    // Phase 5: Exam Management (UC-GV-030 to UC-GV-037)
    Route::post('/exams/{exam}/questions/add', [App\Http\Controllers\Teacher\ExamController::class, 'addQuestions'])->name('exams.questions.add');
    Route::post('/exams/{exam}/questions/create', [App\Http\Controllers\Teacher\ExamController::class, 'createQuestion'])->name('exams.questions.create');
    Route::delete('/exams/{exam}/questions/{examQuestion}', [App\Http\Controllers\Teacher\ExamController::class, 'removeQuestion'])->name('exams.questions.remove');
    Route::put('/exams/{exam}/questions/reorder', [App\Http\Controllers\Teacher\ExamController::class, 'reorderQuestions'])->name('exams.questions.reorder');
    Route::post('/exams/{exam}/publish', [App\Http\Controllers\Teacher\ExamController::class, 'publish'])->name('exams.publish');
    Route::post('/exams/{exam}/notify', [App\Http\Controllers\Teacher\ExamController::class, 'sendNotification'])->name('exams.notify');
    Route::post('/exams/import', [App\Http\Controllers\Teacher\ExamController::class, 'importFromExcel'])->name('exams.import');
    Route::resource('exams', App\Http\Controllers\Teacher\ExamController::class);
    
    // Notifications - Teacher sending to students
    Route::post('/notifications/send-to-students', [App\Http\Controllers\NotificationController::class, 'sendToStudents'])->name('notifications.send-to-students');
    
    // Phase 6: Video Call Management (UC-GV-001 to UC-GV-004)
    Route::post('/video-calls/{videoCall}/start', [App\Http\Controllers\Teacher\VideoCallController::class, 'start'])->name('video-calls.start');
    Route::post('/video-calls/{videoCall}/end', [App\Http\Controllers\Teacher\VideoCallController::class, 'end'])->name('video-calls.end');
    Route::get('/video-calls/{videoCall}/join', [App\Http\Controllers\Teacher\VideoCallController::class, 'join'])->name('video-calls.join');
    Route::post('/video-calls/{videoCall}/invite', [App\Http\Controllers\Teacher\VideoCallController::class, 'invite'])->name('video-calls.invite');
    Route::post('/video-calls/{videoCall}/toggle-recording', [App\Http\Controllers\Teacher\VideoCallController::class, 'toggleRecording'])->name('video-calls.toggle-recording');
    Route::post('/video-calls/{videoCall}/save-recording', [App\Http\Controllers\Teacher\VideoCallController::class, 'saveRecording'])->name('video-calls.save-recording');
    Route::resource('video-calls', App\Http\Controllers\Teacher\VideoCallController::class);
    
    // Phase 7: Grading (UC-GV-080 to UC-GV-084)
    Route::get('/grading', [App\Http\Controllers\Teacher\GradingController::class, 'index'])->name('grading.index');
    Route::get('/grading/{submission}', [App\Http\Controllers\Teacher\GradingController::class, 'show'])->name('grading.show');
    Route::post('/grading/{submission}/auto-grade', [App\Http\Controllers\Teacher\GradingController::class, 'autoGrade'])->name('grading.auto-grade');
    Route::post('/grading/{submission}/grade', [App\Http\Controllers\Teacher\GradingController::class, 'grade'])->name('grading.grade');
    Route::post('/grading/bulk-auto-grade', [App\Http\Controllers\Teacher\GradingController::class, 'bulkAutoGrade'])->name('grading.bulk-auto-grade');
    
    // Phase 8: Reports & Analytics (UC-GV-060 to UC-GV-062)
    Route::get('/reports', [App\Http\Controllers\Teacher\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/subject/{subject}', [App\Http\Controllers\Teacher\ReportsController::class, 'subjectOverview'])->name('reports.subject-overview');
    Route::get('/reports/class/{classRoom}', [App\Http\Controllers\Teacher\ReportsController::class, 'classPerformance'])->name('reports.class-performance');
    Route::get('/reports/student/{classRoom}/{student}', [App\Http\Controllers\Teacher\ReportsController::class, 'studentPerformance'])->name('reports.student-performance');
    Route::get('/reports/exam/{exam}', [App\Http\Controllers\Teacher\ReportsController::class, 'examAnalysis'])->name('reports.exam-analysis');
    Route::get('/reports/export-gradebook/{classRoom}', [App\Http\Controllers\Teacher\ReportsController::class, 'exportGradebook'])->name('reports.export-gradebook');
    Route::get('/reports/print-gradebook/{classRoom}', [App\Http\Controllers\Teacher\ReportsController::class, 'printGradebook'])->name('reports.print-gradebook');
});

// Admin Routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

    // ADMIN COURSES (THEO NEO UI)
    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\CourseController::class, 'index'])
            ->name('index');

        Route::get('/create', [App\Http\Controllers\Admin\CourseController::class, 'create'])
            ->name('create');

        Route::post('/', [App\Http\Controllers\Admin\CourseController::class, 'store'])
            ->name('store');

        Route::get('/{id}/edit', [App\Http\Controllers\Admin\CourseController::class, 'edit'])
            ->name('edit');

        Route::put('/{id}', [App\Http\Controllers\Admin\CourseController::class, 'update'])
            ->name('update');

        Route::delete('/{id}', [App\Http\Controllers\Admin\CourseController::class, 'destroy'])
            ->name('destroy');
    });

// ======================
//  ADMIN PROFILE ROUTES
// ======================
Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])
    ->name('profile');

Route::put('/profile/update', [App\Http\Controllers\Admin\ProfileController::class, 'update'])
    ->name('profile.update');

Route::put('/profile/password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])
    ->name('profile.password');
Route::post('/profile/avatar', [App\Http\Controllers\Admin\ProfileController::class, 'updateAvatar'])
    ->name('profile.avatar');

    /*
    |--------------------------------------------------------------------------
    | 📌 1. DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/', fn() => view('admin.dashboard'))->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | 📌 2. ADMIN → USER MANAGEMENT (tự viết)
    |-------------------------------------------------------------------------- 
    */
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\UserManagementController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | 📌 3. ADMIN → TESTER MANAGEMENT
    |--------------------------------------------------------------------------
    */
    // TODO: Create TesterController before uncommenting
    // Route::prefix('tester')->name('tester.')->group(function () {
    //     Route::get('/', [App\Http\Controllers\Admin\TesterController::class, 'index'])->name('index');
    //     Route::get('/create', [App\Http\Controllers\Admin\TesterController::class, 'create'])->name('create');
    //     Route::post('/', [App\Http\Controllers\Admin\TesterController::class, 'store'])->name('store');
    // });


    /*
    |--------------------------------------------------------------------------
    | 📌 4. ADMIN → VIEW USERS BY ROLE
    |--------------------------------------------------------------------------
    */
 Route::get('/students', [App\Http\Controllers\Admin\UserManagementController::class, 'students'])
    ->name('students.index');

Route::get('/teachers', [App\Http\Controllers\Admin\UserManagementController::class, 'teachers'])
    ->name('teachers.index');

Route::get('/admins', [App\Http\Controllers\Admin\UserManagementController::class, 'admins'])
    ->name('admins.index');

    Route::get('/profile', function () {
    return view('admin.profile');
})->name('profile');

Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])
    ->name('settings');
Route::post('/settings/update-general', [App\Http\Controllers\Admin\SettingsController::class, 'updateGeneral'])
    ->name('settings.update.general');

Route::post('/settings/update-security', [App\Http\Controllers\Admin\SettingsController::class, 'updateSecurity'])
    ->name('settings.update.security');

    /*
    |--------------------------------------------------------------------------
    | 📌 5. SYSTEM ROUTES (GIỮ NGUYÊN)
    |--------------------------------------------------------------------------
    */

    // Statistics Dashboard
    Route::get('/statistics', [App\Http\Controllers\Admin\StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('/statistics/activity-logs', [App\Http\Controllers\Admin\StatisticsController::class, 'activityLogs'])->name('statistics.activity-logs');
    Route::get('/statistics/usage-duration', [App\Http\Controllers\Admin\StatisticsController::class, 'usageDuration'])->name('statistics.usage-duration');
    Route::get('/statistics/participation', [App\Http\Controllers\Admin\StatisticsController::class, 'participation'])->name('statistics.participation');
    Route::get('/statistics/rankings', [App\Http\Controllers\Admin\StatisticsController::class, 'rankings'])->name('statistics.rankings');
    Route::get('/statistics/export', [App\Http\Controllers\Admin\StatisticsController::class, 'export'])->name('statistics.export');


    // User Management (resource)
    Route::resource('users', App\Http\Controllers\Admin\UserManagementController::class);
    Route::post('users/{user}/toggle-lock', [App\Http\Controllers\Admin\UserManagementController::class, 'toggleLock'])->name('users.toggle-lock');
    Route::post('users/{user}/permissions', [App\Http\Controllers\Admin\UserManagementController::class, 'updatePermissions'])->name('users.permissions');


    // Subjects
    Route::resource('subjects', App\Http\Controllers\Admin\SubjectController::class);

    // Categories
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);

    // Lessons
    Route::get('/lessons', fn() => view('admin.lessons.index'))->name('lessons.index');
    Route::get('/lessons/create', fn() => view('admin.lessons.create'))->name('lessons.create');
    Route::post('/lessons', fn() => redirect()->route('admin.lessons.index'))->name('lessons.store');

    // Topics
    Route::get('/topics', fn() => view('admin.topics.index'))->name('topics.index');
    Route::get('/topics/create', fn() => view('admin.topics.create'))->name('topics.create');
    Route::get('/topics/{id}/edit', fn($id) => view('admin.topics.edit', ['id' => $id]))->name('topics.edit');

    // Questions Management (Link với Teacher's Questions)
    Route::resource('questions', App\Http\Controllers\Admin\QuestionManagementController::class);
    Route::post('/questions/bulk-delete', [App\Http\Controllers\Admin\QuestionManagementController::class, 'bulkDestroy'])->name('questions.bulk-delete');

    // Exams Management (Link với Teacher's Exams)
    Route::get('/exams/{exam}/questions', [App\Http\Controllers\Admin\ExamManagementController::class, 'questions'])->name('exams.questions');
    Route::get('/exams/{exam}/results', [App\Http\Controllers\Admin\ExamManagementController::class, 'results'])->name('exams.results');
    Route::post('/exams/{exam}/status', [App\Http\Controllers\Admin\ExamManagementController::class, 'updateStatus'])->name('exams.update-status');
    Route::resource('exams', App\Http\Controllers\Admin\ExamManagementController::class);

    // Exam Results
    Route::get('/exam-results', fn() => view('admin.exam-results.index'))->name('exam-results.index');

    // Forums
    Route::get('/forums/topics', fn() => view('admin.forums.topics'))->name('forums.topics');
    Route::post('/forums/topics', fn() => redirect()->route('admin.forums.topics'))->name('forums.topics.create');
    Route::get('/forums/posts', fn() => view('admin.forums.posts'))->name('forums.posts');
    Route::get('/forums/moderation', fn() => view('admin.forums.moderation'))->name('forums.moderation');

    // Meetings
    Route::get('/meetings/rooms', fn() => view('admin.meetings.rooms'))->name('meetings.rooms');
    Route::post('/meetings/rooms', fn() => redirect()->route('admin.meetings.rooms'))->name('meetings.rooms.create');
    Route::get('/meetings/schedule', fn() => view('admin.meetings.schedule'))->name('meetings.schedule');
    Route::post('/meetings/schedule', fn() => redirect()->route('admin.meetings.schedule'))->name('meetings.schedule.create');
    Route::get('/meetings/history', fn() => view('admin.meetings.history'))->name('meetings.history');

    // Files
    Route::get('/files', fn() => view('admin.files.index'))->name('files.index');
    Route::get('/files/upload', fn() => view('admin.files.upload'))->name('files.upload');
    Route::post('/files', fn() => redirect()->route('admin.files.index'))->name('files.store');

    // Reports
    Route::get('/reports/students', fn() => view('admin.reports.students'))->name('reports.students');
    Route::get('/reports/courses', fn() => view('admin.reports.courses'))->name('reports.courses');
    Route::get('/reports/exams', fn() => view('admin.reports.exams'))->name('reports.exams');

    // Reports
    Route::get('/reports/students', fn() => view('admin.reports.students'))->name('reports.students');
    Route::get('/reports/courses', fn() => view('admin.reports.courses'))->name('reports.courses');
    Route::get('/reports/exams', fn() => view('admin.reports.exams'))->name('reports.exams');

    // Statistics (detailed analytics)
    Route::get('/statistics', fn() => view('admin.statistics.index'))->name('statistics.index');
    Route::get('/statistics/activity-logs', fn() => view('admin.statistics.activity-logs'))->name('statistics.activity-logs');
    Route::get('/statistics/usage-duration', fn() => view('admin.statistics.usage-duration'))->name('statistics.usage-duration');
    Route::get('/statistics/participation', fn() => view('admin.statistics.participation'))->name('statistics.participation');
    Route::get('/statistics/rankings', fn() => view('admin.statistics.rankings'))->name('statistics.rankings');

    // Settings
    Route::get('/settings/email', fn() => view('admin.settings.email'))->name('settings.email');
    Route::put('/settings/email', fn() => redirect()->route('admin.settings.email'))->name('settings.email.update');
    Route::get('/settings/payment', fn() => view('admin.settings.payment'))->name('settings.payment');
    Route::put('/settings/payment', fn() => redirect()->route('admin.settings.payment'))->name('settings.payment.update');

});

// forum routes

Route::prefix('forum')->middleware(['auth'])->group(function () {
    Route::get('/', [ForumQuestionController::class, 'index'])->name('forum.index');
    Route::get('/back', [ForumQuestionController::class, 'back'])->name('forum.back');
    Route::get('/create', [ForumQuestionController::class, 'create'])->name('forum.create');
    Route::post('/', [ForumQuestionController::class, 'store'])->name('forum.store');
    Route::get('/{forumQuestion}', [ForumQuestionController::class, 'show'])->name('forum.show');
    Route::get('/{forumQuestion}/edit', [ForumQuestionController::class, 'edit'])->name('forum.edit');
    Route::put('/{forumQuestion}', [ForumQuestionController::class, 'update'])->name('forum.update');
    Route::delete('/{forumQuestion}', [ForumQuestionController::class, 'destroy'])->name('forum.destroy');
    Route::post('/{forumQuestion}/vote/up', [ForumQuestionController::class, 'voteUp'])->name('forum.vote.up');
    Route::post('/{forumQuestion}/vote/down', [ForumQuestionController::class, 'voteDown'])->name('forum.vote.down');
    Route::post('/{forumQuestion}/answers', [ForumQuestionController::class, 'storeAnswer'])->name('forum.answer.store');
    Route::post('/{forumQuestion}/answers/{forumAnswer}/vote/up', [ForumQuestionController::class, 'voteAnswerUp'])->name('forum.answer.vote.up');
    Route::post('/{forumQuestion}/answers/{forumAnswer}/vote/down', [ForumQuestionController::class, 'voteAnswerDown'])->name('forum.answer.vote.down');
    Route::delete('/{forumQuestion}/answers/{forumAnswer}', [ForumQuestionController::class, 'destroyAnswer'])->name('forum.answer.destroy');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot Password Routes (UC-GLOBAL-003)
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

// Profile Routes (UC-GLOBAL-004: Manage Profile)
Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('update');
    Route::get('/password', [ProfileController::class, 'editPassword'])->name('password');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/avatar', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
});

Route::get('/', function () {
    return view('welcome');
});

// Public chat route (for demo/testing without auth)
Route::get('/chat', function () {
    return view('chat.index');
})->name('chat');

// Chat demo (alternative route)
Route::get('/chat-demo', function () {
    return view('chat.index');
})->name('chat.demo');

// Chat Routes (accessible by authenticated users) - if you need authenticated version
Route::middleware(['auth'])->prefix('chat-auth')->name('chat.auth.')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('index');
});

// Universal Dashboard Route (redirects based on role)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('teacher')) {
            return redirect()->route('teacher.dashboard');
        } elseif ($user->hasRole('student')) {
            return redirect()->route('student.dashboard');
        }
        
        // Default fallback
        return view('dashboard.index');
    })->name('dashboard');
});

// Student Dashboard (authenticated students)
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'welcome'])->name('dashboard');
    Route::get('/welcome', [StudentController::class, 'welcome'])->name('welcome'); // Keep for backward compatibility
});

// ⚠️ Duplicate admin routes removed - all admin routes are defined above in the main admin group (lines 206-351)

// forum routes

Route::prefix('forum')->middleware(['auth'])->group(function () {
    Route::get('/', [ForumQuestionController::class, 'index'])->name('forum.index');
    Route::get('/back', [ForumQuestionController::class, 'back'])->name('forum.back');
    Route::get('/create', [ForumQuestionController::class, 'create'])->name('forum.create');
    Route::post('/', [ForumQuestionController::class, 'store'])->name('forum.store');
    Route::get('/{forumQuestion}', [ForumQuestionController::class, 'show'])->name('forum.show');
    Route::get('/{forumQuestion}/edit', [ForumQuestionController::class, 'edit'])->name('forum.edit');
    Route::put('/{forumQuestion}', [ForumQuestionController::class, 'update'])->name('forum.update');
    Route::delete('/{forumQuestion}', [ForumQuestionController::class, 'destroy'])->name('forum.destroy');
    Route::post('/{forumQuestion}/vote/up', [ForumQuestionController::class, 'voteUp'])->name('forum.vote.up');
    Route::post('/{forumQuestion}/vote/down', [ForumQuestionController::class, 'voteDown'])->name('forum.vote.down');
    Route::post('/{forumQuestion}/answers', [ForumQuestionController::class, 'storeAnswer'])->name('forum.answer.store');
    Route::post('/{forumQuestion}/answers/{forumAnswer}/vote/up', [ForumQuestionController::class, 'voteAnswerUp'])->name('forum.answer.vote.up');
    Route::post('/{forumQuestion}/answers/{forumAnswer}/vote/down', [ForumQuestionController::class, 'voteAnswerDown'])->name('forum.answer.vote.down');
    Route::delete('/{forumQuestion}/answers/{forumAnswer}', [ForumQuestionController::class, 'destroyAnswer'])->name('forum.answer.destroy');
});