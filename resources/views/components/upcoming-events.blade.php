<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Home -> chuyển về login
Route::get('/', fn () => redirect()->route('login'))->name('home');

// Nếu file này có route auth riêng, đảm bảo KHÔNG trùng /login, /register
require __DIR__.'/auth_admin_user.php';

// ===== Guest (chưa đăng nhập) =====
Route::middleware('guest')->group(function () {
    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');        // form
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');      // submit

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ===== User (đã đăng nhập) =====
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        // Nếu là admin mà vào /dashboard thì chuyển qua admin
        if (auth()->user()?->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard'); // đảm bảo đã có resources/views/dashboard.blade.php
    })->name('dashboard');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success','Đã đăng xuất.');
    })->name('logout');
});

// ===== Admin =====
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', AdminMiddleware::class])
    ->group(function () {
        Route::get('/', fn () => view('admin.dashboard'))->name('dashboard');

        Route::prefix('subjects')->name('subjects.')->group(function () {
            Route::get('/', fn () => view('admin.subjects.index'))->name('index');
            Route::get('/create', fn () => view('admin.subjects.create'))->name('create');
            Route::get('/{id}', fn ($id) => view('admin.subjects.show', compact('id')))->name('show');
            Route::get('/{id}/edit', fn ($id) => view('admin.subjects.edit', compact('id')))->name('edit');
        });

        Route::prefix('topics')->name('topics.')->group(function () {
            Route::get('/', fn () => view('admin.topics.index'))->name('index');
            Route::get('/create', fn () => view('admin.topics.create'))->name('create');
            Route::get('/{id}/edit', fn ($id) => view('admin.topics.edit', compact('id')))->name('edit');
        });

        Route::prefix('questions')->name('questions.')->group(function () {
            Route::get('/', fn () => view('admin.questions.index'))->name('index');
            Route::get('/create', fn () => view('admin.questions.create'))->name('create');
            Route::get('/{id}/edit', fn ($id) => view('admin.questions.edit', compact('id')))->name('edit');
        });

        Route::prefix('exams')->name('exams.')->group(function () {
            Route::get('/', fn () => view('admin.exams.index'))->name('index');
            Route::get('/create', fn () => view('admin.exams.create'))->name('create');
            Route::get('/{id}/edit', fn ($id) => view('admin.exams.edit', compact('id')))->name('edit');
        });
    });
