<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ForumQuestionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
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

// Chat route - No auth middleware to avoid session conflicts
Route::get('/chat', [ChatController::class, 'index'])->name('chat');

// Chat demo (public - for testing without auth)
Route::get('/chat-demo', function () {
    return view('chat.index');
})->name('chat.demo');

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
    
    // Phase 2: Document Management (UC-GV-070 to UC-GV-074)
    Route::resource('documents', App\Http\Controllers\Teacher\DocumentController::class);
    Route::post('/documents/folder', [App\Http\Controllers\Teacher\DocumentController::class, 'createFolder'])->name('documents.folder.create');
    Route::post('/documents/{document}/move', [App\Http\Controllers\Teacher\DocumentController::class, 'moveToFolder'])->name('documents.move');
    Route::get('/documents/{document}/download', [App\Http\Controllers\Teacher\DocumentController::class, 'download'])->name('documents.download');
    Route::post('/documents/{document}/approve', [App\Http\Controllers\Teacher\DocumentController::class, 'approve'])->name('documents.approve');
    Route::post('/documents/{document}/reject', [App\Http\Controllers\Teacher\DocumentController::class, 'reject'])->name('documents.reject');
    
    // Phase 3: Student Management (UC-GV-050 to UC-GV-054)
    Route::get('/students', [App\Http\Controllers\Teacher\StudentController::class, 'index'])->name('students.index');
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
    
    // Legacy routes (to be refactored)
    Route::get('/topics', [TeacherController::class, 'topics'])->name('topics');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // UC-ADM-050: Statistics Dashboard
    Route::get('/statistics', [App\Http\Controllers\Admin\StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('/statistics/activity-logs', [App\Http\Controllers\Admin\StatisticsController::class, 'activityLogs'])->name('statistics.activity-logs');
    Route::get('/statistics/usage-duration', [App\Http\Controllers\Admin\StatisticsController::class, 'usageDuration'])->name('statistics.usage-duration');
    Route::get('/statistics/participation', [App\Http\Controllers\Admin\StatisticsController::class, 'participation'])->name('statistics.participation');
    Route::get('/statistics/rankings', [App\Http\Controllers\Admin\StatisticsController::class, 'rankings'])->name('statistics.rankings');
    Route::get('/statistics/export', [App\Http\Controllers\Admin\StatisticsController::class, 'export'])->name('statistics.export');

    // User Management (UC-ADM-010 to UC-ADM-015)
    Route::resource('users', App\Http\Controllers\Admin\UserManagementController::class);
    Route::post('users/{user}/toggle-lock', [App\Http\Controllers\Admin\UserManagementController::class, 'toggleLock'])->name('users.toggle-lock');
    Route::post('users/{user}/permissions', [App\Http\Controllers\Admin\UserManagementController::class, 'updatePermissions'])->name('users.permissions');

    // Subjects Management
    Route::get('/subjects', function () {
        return view('admin.subjects.index');
    })->name('subjects.index');

    Route::get('/subjects/create', function () {
        return view('admin.subjects.create');
    })->name('subjects.create');

    Route::get('/subjects/{id}', function ($id) {
        return view('admin.subjects.show', ['id' => $id]);
    })->name('subjects.show');

    Route::get('/subjects/{id}/edit', function ($id) {
        return view('admin.subjects.edit', ['id' => $id]);
    })->name('subjects.edit');

    // Topics Management
    Route::get('/topics', function () {
        return view('admin.topics.index');
    })->name('topics.index');

    Route::get('/topics/create', function () {
        return view('admin.topics.create');
    })->name('topics.create');

    Route::get('/topics/{id}/edit', function ($id) {
        return view('admin.topics.edit', ['id' => $id]);
    })->name('topics.edit');

    // Questions Management
    Route::get('/questions', function () {
        return view('admin.questions.index');
    })->name('questions.index');

    Route::get('/questions/create', function () {
        return view('admin.questions.create');
    })->name('questions.create');

    Route::get('/questions/{id}/edit', function ($id) {
        return view('admin.questions.edit', ['id' => $id]);
    })->name('questions.edit');

    // Exams Management
    Route::get('/exams', function () {
        return view('admin.exams.index');
    })->name('exams.index');

    Route::get('/exams/create', function () {
        return view('admin.exams.create');
    })->name('exams.create');

    Route::get('/exams/{id}/edit', function ($id) {
        return view('admin.exams.edit', ['id' => $id]);
    })->name('exams.edit');
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