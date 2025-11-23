<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
<<<<<<< .merge_file_2tsHox
use App\Http\Controllers\ForumQuestionController;
=======
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
>>>>>>> .merge_file_XixHoc

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
    
    // Legacy routes (to be refactored)
    Route::get('/topics', [TeacherController::class, 'topics'])->name('topics');
    Route::get('/questions', [TeacherController::class, 'questions'])->name('questions');
    Route::get('/exams', [TeacherController::class, 'exams'])->name('exams');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

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