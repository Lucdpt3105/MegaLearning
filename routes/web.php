<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\AuthController; // <-- thêm
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// =================== Home -> luôn tới trang login ===================
Route::get('/', function () {
    return redirect()->route('login'); // <-- đưa thẳng về form đăng nhập
})->name('home');

// =================== Auth (User & Admin) ===================
// PHẢI include ngoài mọi nhóm prefix('admin')
require __DIR__.'/auth_admin_user.php';

// === Register (User) ===
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');



// =================== USER AREA (chỉ vào khi đã đăng nhập) ===================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        // Nếu là admin mà lỡ vào /dashboard thì chuyển về /admin
        if (auth()->user()?->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        // đổi view này nếu bạn có trang dashboard riêng cho user
        return view('welcome', ['slot' => 'User Dashboard']);
    })->name('user.dashboard');
});

// =================== ADMIN AREA (auth + is_admin) ===================
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', AdminMiddleware::class])
    ->group(function () {
        Route::get('/', fn () => view('admin.dashboard'))->name('dashboard');

        Route::prefix('subjects')->name('subjects.')->group(function () {
            Route::get('/', fn () => view('admin.subjects.index'))->name('index');
            Route::get('/create', fn () => view('admin.subjects.create'))->name('create');
            Route::get('/{id}', fn ($id) => view('admin.subjects.show', ['id'=>$id]))->name('show');
            Route::get('/{id}/edit', fn ($id) => view('admin.subjects.edit', ['id'=>$id]))->name('edit');
        });

        Route::prefix('topics')->name('topics.')->group(function () {
            Route::get('/', fn () => view('admin.topics.index'))->name('index');
            Route::get('/create', fn () => view('admin.topics.create'))->name('create');
            Route::get('/{id}/edit', fn ($id) => view('admin.topics.edit', ['id'=>$id]))->name('edit');
        });

        Route::prefix('questions')->name('questions.')->group(function () {
            Route::get('/', fn () => view('admin.questions.index'))->name('index');
            Route::get('/create', fn () => view('admin.questions.create'))->name('create');
            Route::get('/{id}/edit', fn ($id) => view('admin.questions.edit', ['id'=>$id]))->name('edit');
        });

        Route::prefix('exams')->name('exams.')->group(function () {
            Route::get('/', fn () => view('admin.exams.index'))->name('index');
            Route::get('/create', fn () => view('admin.exams.create'))->name('create');
            Route::get('/{id}/edit', fn ($id) => view('admin.exams.edit', ['id'=>$id]))->name('edit');
        });
    });

 Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success','Đã đăng xuất.');
    })->name('logout');
});