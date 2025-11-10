<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;

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
