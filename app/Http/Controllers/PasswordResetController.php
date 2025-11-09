<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    // Form nhập email
    public function request()
    {
        return view('auth.forgot_password');
    }

    // Gửi mail chứa link reset
    public function email(Request $request)
    {
        $request->validate(['email' => ['required','email']]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        throw ValidationException::withMessages(['email' => __($status)]);
    }

    // Form đặt mật khẩu mới từ link trong mail
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset_password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    // Xử lý đặt mật khẩu mới
    public function reset(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required','email'],
            'password' => ['required','confirmed','min:6'],
        ]);

        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function ($user, $password) {
                // Model User đã cast 'password' => 'hashed'
                $user->forceFill([
                    'password'       => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        throw ValidationException::withMessages(['email' => __($status)]);
    }
}
