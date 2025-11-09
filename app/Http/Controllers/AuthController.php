<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;   // <-- THÊM
use App\Models\User;                   // <-- THÊM

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
            'user_name'     => $data['name'] ?? null,               // cột trong bảng của bạn
            'email'         => $data['email'],
            'password'      => $data['password'],                   // sẽ được hash tự động
            'role'          => 'user',
            'status'        => 'active',
            // đồng bộ bộ cột cũ để tương thích
            'user_email'    => $data['email'],
            'user_password' => Hash::make($data['password']),
            'user_role'     => 'user',
            'created_at'    => now(),                               // nếu bảng có cột này
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('user.dashboard');
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
