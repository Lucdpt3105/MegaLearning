<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;   // <-- THÊM
use App\Models\User;                   // <-- THÊM
use Illuminate\Auth\Events\Registered;


class AuthController extends Controller
{
    public function showUserLogin()
    {
        return view('auth.login_user');
    }

    public function showAdminLogin()
    {
        return view('auth.login_admin');
    }

    // --- REGISTER ---
    public function showRegister()
    {
        return view('auth.register_user');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['nullable','string','max:50'],           // map vào user_name
            'email'    => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:6','confirmed'],
        ]);

        // password sẽ tự hash nhờ casts 'password' => 'hashed' trong Model User
         $user = User::create([
        'name'     => $data['name'] ?? null,
        'email'    => $data['email'],
        'password' => $data['password'],
        'role'     => 'user',
        'status'   => 'active',
    ]);// phát sự kiện để Laravel gửi email xác minh
    

    // đăng nhập, nhưng chưa verified sẽ bị chặn vào dashboard


    return redirect()->route('login')
        ->with('success', 'Đăng ký thành công! Vui lòng đăng nhập để tiếp tục.');
    }

    public function userLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','string','min:6'],
        ]);
        $remember = $request->boolean('remember');

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'role' => 'user'
        ], $remember)) {
            $request->session()->regenerate();
            return redirect()->route('user.dashboard');
        }

        throw ValidationException::withMessages([
            'email' => 'Sai tài khoản/mật khẩu hoặc không phải user.',
        ]);
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','string','min:6'],
        ]);
        $remember = $request->boolean('remember');

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'role' => 'admin'
        ], $remember)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        throw ValidationException::withMessages([
            'email' => 'Sai tài khoản/mật khẩu hoặc không phải admin.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
