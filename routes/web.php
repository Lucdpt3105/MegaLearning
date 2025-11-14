<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ForumQuestionController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('welcome');
});

// Student Dashboard (authenticated students)
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('welcome', [StudentController::class, 'welcome'])->name('welcome');
});

// Teacher Routes
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/subjects', [TeacherController::class, 'subjects'])->name('subjects');
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