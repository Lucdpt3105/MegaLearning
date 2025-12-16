<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\AdminNotificationService;

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
            
            // Update last login time
            $user->update(['last_login_at' => now()]);

            // Redirect based on role
            if ($user->hasRole('admin')) {
                return redirect()->intended('/admin'); // Admin route: /admin
            } elseif ($user->hasRole('teacher')) {
                return redirect()->intended('/teacher/dashboard');
            } else {
                return redirect()->intended('/student/dashboard'); // Student route: /student/dashboard
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
            'password' => 'required|min:8|confirmed',
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

        Auth::login($user);

        return redirect('/student/dashboard')->with('success', 'Đăng ký thành công! Chào mừng bạn đến với MegaLearning 🎉');
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
