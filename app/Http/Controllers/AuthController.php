<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Services\AdminNotificationService;
use App\Notifications\WelcomeNotification;

class AuthController extends Controller
{
    protected $adminNotificationService;

    public function __construct(AdminNotificationService $adminNotificationService)
    {
        $this->adminNotificationService = $adminNotificationService;
    }

    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return redirect('/login')->withErrors([
                    'email' => 'Vui lòng xác thực email trước khi đăng nhập. Kiểm tra hộp thư của bạn.',
                ])->withInput($request->only('email'));
            }
            
            // Update last login time
            $user->update(['last_login_at' => now()]);

            // Redirect based on role
            if ($user->hasRole('admin')) {
                return redirect()->intended('/admin');
            } elseif ($user->hasRole('teacher')) {
                return redirect()->intended('/teacher/dashboard');
            } else {
                return redirect()->intended('/student/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->onlyInput('email');
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle register request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',      // Ít nhất 1 chữ hoa
                'regex:/[a-z]/',      // Ít nhất 1 chữ thường
                'regex:/[0-9]/',      // Ít nhất 1 số
                'regex:/[@$!%*#?&]/', // Ít nhất 1 ký tự đặc biệt
            ],
        ], [
            'password.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt (@$!%*#?&).',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Default role: student
        $user->assignRole('student');

        // Gửi thông báo cho admin về người dùng mới
        $this->adminNotificationService->notifyNewStudentRegistration($user);

        // Fire Registered event — sends verification email
        event(new Registered($user));

        // Redirect to verification notice (NOT auto-login)
        return redirect('/email/verify-notice')->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản. 📧');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Đã đăng xuất!');
    }
}
