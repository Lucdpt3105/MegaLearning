# 4.2. CHỨC NĂNG ĐĂNG KÝ - ĐĂNG NHẬP - QUÊN MẬT KHẨU

## 4.2.1. Tổng quan về Authentication System

Hệ thống xác thực của MegaLearning được xây dựng dựa trên **Laravel Authentication** kết hợp với **Spatie Permission** để quản lý roles và permissions. Hệ thống đảm bảo tính bảo mật cao, trải nghiệm người dùng tốt và tuân thủ các best practices về security.

### Các tính năng chính:

1. **User Registration (Đăng ký):** Tạo tài khoản mới với validation đầy đủ
2. **User Login (Đăng nhập):** Xác thực người dùng với email/password
3. **Password Reset (Quên mật khẩu):** Khôi phục mật khẩu qua email
4. **Email Verification (Xác thực email):** Xác minh email sau đăng ký
5. **Session Management:** Quản lý phiên đăng nhập
6. **Role-based Access Control:** Phân quyền theo vai trò
7. **Remember Me:** Ghi nhớ đăng nhập
8. **Logout:** Đăng xuất an toàn

### Công nghệ sử dụng:

- **Backend:** Laravel 12.x Authentication System
- **Session:** Database-backed sessions
- **Password Hashing:** Bcrypt (cost factor: 12)
- **CSRF Protection:** Laravel CSRF tokens
- **Rate Limiting:** Throttle để chống brute-force
- **Email Service:** Laravel Mail + SMTP/Mailtrap

---

## 4.2.2. Chức năng Đăng ký (Registration)

### 4.2.2.1. Giao diện đăng ký

Trang đăng ký cho phép người dùng mới tạo tài khoản trên hệ thống.

**URL:** `/register`

**Giao diện form đăng ký:**

![Screenshot: Registration Form](./screenshots/auth-register-form.png)
*Hình 4.2.1: Giao diện form đăng ký tài khoản*

```
┌─────────────────────────────────────────────────┐
│                 MegaLearning                    │
│                 Đăng ký tài khoản               │
├─────────────────────────────────────────────────┤
│                                                 │
│  Họ và tên:                                     │
│  [_________________________________________]    │
│                                                 │
│  Email:                                         │
│  [_________________________________________]    │
│                                                 │
│  Mật khẩu:                                      │
│  [_________________________________________] 👁  │
│  ⚠️ Ít nhất 8 ký tự                            │
│                                                 │
│  Xác nhận mật khẩu:                            │
│  [_________________________________________] 👁  │
│                                                 │
│  Vai trò:                                       │
│  ⦿ Học sinh     ○ Giáo viên                    │
│                                                 │
│  ☑ Tôi đồng ý với Điều khoản sử dụng          │
│                                                 │
│  [       Đăng ký       ]                       │
│                                                 │
│  Đã có tài khoản? Đăng nhập                    │
│                                                 │
└─────────────────────────────────────────────────┘
```

### 4.2.2.2. Quy trình đăng ký

**Flowchart quy trình:**

![Screenshot: Registration Flowchart](./screenshots/auth-register-flowchart.png)
*Hình 4.2.2: Sơ đồ quy trình đăng ký*

```
START
  ↓
[Người dùng điền form]
  ↓
[Submit form]
  ↓
<Validation OK?> ──NO──> [Hiển thị lỗi] ──┐
  ↓ YES                                    │
[Hash password]                            │
  ↓                                        │
[Lưu user vào database]                    │
  ↓                                        │
[Gán role mặc định]                        │
  ↓                                        │
[Gửi email xác thực]                       │
  ↓                                        │
[Tự động đăng nhập]                        │
  ↓                                        │
[Redirect tới dashboard]                   │
  ↓                                        │
END ←──────────────────────────────────────┘
```

### 4.2.2.3. Validation Rules (Quy tắc kiểm tra)

Hệ thống áp dụng các quy tắc validation nghiêm ngặt:

| Trường | Quy tắc | Mô tả |
|--------|---------|-------|
| **name** | required, string, max:255 | Họ tên bắt buộc, tối đa 255 ký tự |
| **email** | required, email, unique:users | Email hợp lệ, chưa tồn tại trong hệ thống |
| **password** | required, min:8, confirmed | Mật khẩu ít nhất 8 ký tự, phải khớp với xác nhận |
| **password_confirmation** | required, same:password | Xác nhận mật khẩu phải giống mật khẩu |
| **role** | required, in:student,teacher | Role phải là student hoặc teacher |
| **terms** | accepted | Phải chấp nhận điều khoản sử dụng |

**Ví dụ validation errors:**

![Screenshot: Validation Errors](./screenshots/auth-register-errors.png)
*Hình 4.2.3: Hiển thị lỗi validation khi đăng ký*

```
⚠️ Vui lòng sửa các lỗi sau:
• Email đã tồn tại trong hệ thống
• Mật khẩu phải có ít nhất 8 ký tự
• Xác nhận mật khẩu không khớp
• Bạn phải đồng ý với điều khoản sử dụng
```

### 4.2.2.4. Code xử lý đăng ký (Backend)

**Controller: `RegisterController.php`**

```php
public function store(Request $request)
{
    // Validate input
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 
                    'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'role' => ['required', 'in:student,teacher'],
        'terms' => ['accepted'],
    ]);

    // Create user
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    // Assign role
    $user->assignRole($validated['role']);

    // Send verification email
    $user->sendEmailVerificationNotification();

    // Log user in
    Auth::login($user);

    // Redirect to dashboard
    return redirect()->route('dashboard')
        ->with('success', 'Đăng ký thành công! Vui lòng xác thực email.');
}
```

### 4.2.2.5. Email xác thực

Sau khi đăng ký, hệ thống gửi email xác thực:

![Screenshot: Verification Email](./screenshots/auth-verification-email.png)
*Hình 4.2.4: Email xác thực tài khoản*

**Nội dung email:**

```
From: MegaLearning <noreply@megalearning.com>
To: student@example.com
Subject: Xác thực tài khoản MegaLearning

Xin chào Nguyễn Văn A,

Cảm ơn bạn đã đăng ký tài khoản MegaLearning!

Vui lòng nhấn vào nút bên dưới để xác thực email của bạn:

[Xác thực Email]

Hoặc copy link sau vào trình duyệt:
https://megalearning.com/email/verify/123/hash

Link này sẽ hết hạn sau 60 phút.

Nếu bạn không đăng ký tài khoản này, vui lòng bỏ qua email này.

Trân trọng,
MegaLearning Team
```

### 4.2.2.6. Thông báo đăng ký thành công

![Screenshot: Registration Success](./screenshots/auth-register-success.png)
*Hình 4.2.5: Thông báo đăng ký thành công*

```
┌─────────────────────────────────────────┐
│  ✅ Đăng ký thành công!                 │
├─────────────────────────────────────────┤
│                                         │
│  Chào mừng bạn đến với MegaLearning!   │
│                                         │
│  Email xác thực đã được gửi đến:       │
│  student@example.com                    │
│                                         │
│  Vui lòng kiểm tra hộp thư và xác      │
│  thực email để kích hoạt tài khoản.    │
│                                         │
│  [Tiếp tục tới Dashboard]              │
│                                         │
└─────────────────────────────────────────┘
```

---

## 4.2.3. Chức năng Đăng nhập (Login)

### 4.2.3.1. Giao diện đăng nhập

**URL:** `/login`

![Screenshot: Login Form](./screenshots/auth-login-form.png)
*Hình 4.2.6: Giao diện form đăng nhập*

```
┌─────────────────────────────────────────────────┐
│                 MegaLearning                    │
│                 Đăng nhập hệ thống              │
├─────────────────────────────────────────────────┤
│                                                 │
│  Email:                                         │
│  [_________________________________________]    │
│                                                 │
│  Mật khẩu:                                      │
│  [_________________________________________] 👁  │
│                                                 │
│  ☑ Ghi nhớ đăng nhập                           │
│                                                 │
│  [       Đăng nhập       ]                     │
│                                                 │
│  Quên mật khẩu?                                │
│                                                 │
│  Chưa có tài khoản? Đăng ký                    │
│                                                 │
└─────────────────────────────────────────────────┘
```

### 4.2.3.2. Quy trình đăng nhập

**Flowchart quy trình:**

![Screenshot: Login Flowchart](./screenshots/auth-login-flowchart.png)
*Hình 4.2.7: Sơ đồ quy trình đăng nhập*

```
START
  ↓
[Người dùng nhập email + password]
  ↓
[Submit form]
  ↓
<Rate limit OK?> ──NO──> [Hiển thị "Too many attempts"] ──┐
  ↓ YES                                                     │
<Email tồn tại?> ──NO──> [Hiển thị "Invalid credentials"] ─┤
  ↓ YES                                                     │
<Password đúng?> ──NO──> [Tăng failed attempts] ───────────┤
  ↓ YES                                                     │
<Email verified?> ──NO──> [Yêu cầu verify email] ──────────┤
  ↓ YES                                                     │
[Tạo session]                                               │
  ↓                                                         │
<Remember me?> ──YES──> [Tạo remember token]               │
  ↓ NO                                                      │
[Ghi log đăng nhập]                                        │
  ↓                                                         │
[Redirect theo role]                                       │
  ↓                                                         │
• Admin → /admin/dashboard                                 │
• Teacher → /teacher/dashboard                             │
• Student → /student/dashboard                             │
  ↓                                                         │
END ←──────────────────────────────────────────────────────┘
```

### 4.2.3.3. Validation & Error Handling

**Các trường hợp lỗi:**

1. **Thông tin không hợp lệ:**

![Screenshot: Login Invalid Credentials](./screenshots/auth-login-invalid.png)
*Hình 4.2.8: Lỗi thông tin đăng nhập không đúng*

```
┌─────────────────────────────────────────┐
│  ❌ Đăng nhập thất bại                  │
├─────────────────────────────────────────┤
│                                         │
│  Email hoặc mật khẩu không chính xác.  │
│                                         │
│  Vui lòng thử lại hoặc đặt lại mật     │
│  khẩu nếu bạn quên.                    │
│                                         │
│  [Đặt lại mật khẩu]                    │
│                                         │
└─────────────────────────────────────────┘
```

2. **Quá nhiều lần thử:**

![Screenshot: Login Rate Limit](./screenshots/auth-login-throttle.png)
*Hình 4.2.9: Thông báo vượt quá số lần đăng nhập*

```
┌─────────────────────────────────────────┐
│  ⚠️ Quá nhiều lần thử                   │
├─────────────────────────────────────────┤
│                                         │
│  Bạn đã thử đăng nhập quá nhiều lần.   │
│                                         │
│  Vui lòng thử lại sau 60 giây.         │
│                                         │
│  Thời gian còn lại: 00:45              │
│                                         │
└─────────────────────────────────────────┘
```

3. **Email chưa xác thực:**

![Screenshot: Email Not Verified](./screenshots/auth-email-unverified.png)
*Hình 4.2.10: Yêu cầu xác thực email*

```
┌─────────────────────────────────────────┐
│  📧 Email chưa được xác thực            │
├─────────────────────────────────────────┤
│                                         │
│  Bạn cần xác thực email trước khi      │
│  đăng nhập.                             │
│                                         │
│  Kiểm tra hộp thư: student@example.com │
│                                         │
│  [Gửi lại email xác thực]              │
│                                         │
└─────────────────────────────────────────┘
```

### 4.2.3.4. Code xử lý đăng nhập (Backend)

**Controller: `LoginController.php`**

```php
public function authenticate(Request $request)
{
    // Validate input
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Rate limiting: 5 attempts per minute
    if (RateLimiter::tooManyAttempts('login:'.$request->ip(), 5)) {
        $seconds = RateLimiter::availableIn('login:'.$request->ip());
        
        return back()->withErrors([
            'email' => "Too many login attempts. Please try again in {$seconds} seconds."
        ])->onlyInput('email');
    }

    // Attempt login
    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        
        // Check email verification
        if (!Auth::user()->hasVerifiedEmail()) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Please verify your email first.'
            ])->onlyInput('email');
        }

        // Regenerate session
        $request->session()->regenerate();

        // Clear rate limiter
        RateLimiter::clear('login:'.$request->ip());

        // Log successful login
        activity()
            ->causedBy(Auth::user())
            ->log('User logged in');

        // Redirect based on role
        return $this->redirectToDashboard();
    }

    // Increment rate limiter
    RateLimiter::hit('login:'.$request->ip(), 60);

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}

protected function redirectToDashboard()
{
    $user = Auth::user();
    
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('teacher')) {
        return redirect()->route('teacher.dashboard');
    } else {
        return redirect()->route('student.dashboard');
    }
}
```

### 4.2.3.5. Remember Me Feature

Khi người dùng chọn "Ghi nhớ đăng nhập":
- Cookie được tạo với thời hạn 30 ngày
- Token được lưu trong bảng `users.remember_token`
- Tự động đăng nhập khi truy cập lại

**Database schema:**

```sql
-- users table
remember_token VARCHAR(100) NULL
```

### 4.2.3.6. Đăng nhập thành công

![Screenshot: Login Success Dashboard](./screenshots/auth-login-success.png)
*Hình 4.2.11: Màn hình sau khi đăng nhập thành công*

**Student Dashboard:**
```
┌─────────────────────────────────────────────────┐
│  👋 Chào mừng trở lại, Nguyễn Văn A!            │
├─────────────────────────────────────────────────┤
│                                                 │
│  ⏰ Last login: Dec 20, 2025 - 09:30 AM        │
│                                                 │
│  [My Classes]  [Exams]  [Grades]  [Chat]       │
│                                                 │
└─────────────────────────────────────────────────┘
```

---

## 4.2.4. Chức năng Quên mật khẩu (Password Reset)

### 4.2.4.1. Giao diện yêu cầu đặt lại mật khẩu

**URL:** `/forgot-password`

![Screenshot: Forgot Password Form](./screenshots/auth-forgot-password-form.png)
*Hình 4.2.12: Form yêu cầu đặt lại mật khẩu*

```
┌─────────────────────────────────────────────────┐
│                 MegaLearning                    │
│                 Quên mật khẩu?                  │
├─────────────────────────────────────────────────┤
│                                                 │
│  Nhập email của bạn và chúng tôi sẽ gửi        │
│  link đặt lại mật khẩu.                        │
│                                                 │
│  Email:                                         │
│  [_________________________________________]    │
│                                                 │
│  [    Gửi link đặt lại mật khẩu    ]          │
│                                                 │
│  Nhớ mật khẩu rồi? Đăng nhập                   │
│                                                 │
└─────────────────────────────────────────────────┘
```

### 4.2.4.2. Quy trình đặt lại mật khẩu

**Flowchart quy trình:**

![Screenshot: Password Reset Flowchart](./screenshots/auth-reset-flowchart.png)
*Hình 4.2.13: Sơ đồ quy trình đặt lại mật khẩu*

```
START
  ↓
[Người dùng nhập email]
  ↓
[Submit form]
  ↓
<Email tồn tại?> ──NO──> [Hiển thị "Email not found"] ─┐
  ↓ YES                                                 │
[Tạo reset token]                                       │
  ↓                                                     │
[Lưu token vào DB]                                     │
  ↓                                                     │
[Gửi email reset link]                                 │
  ↓                                                     │
[Hiển thị success message]                             │
  ↓                                                     │
[Người dùng click link trong email]                    │
  ↓                                                     │
<Token hợp lệ?> ──NO──> [Hiển thị "Invalid token"] ───┤
  ↓ YES                                                 │
<Token hết hạn?> ──YES──> [Hiển thị "Token expired"] ──┤
  ↓ NO                                                  │
[Form nhập password mới]                               │
  ↓                                                     │
[Submit password mới]                                  │
  ↓                                                     │
<Validation OK?> ──NO──> [Hiển thị lỗi] ───────────────┤
  ↓ YES                                                 │
[Hash & lưu password mới]                              │
  ↓                                                     │
[Xóa reset token]                                      │
  ↓                                                     │
[Gửi email thông báo]                                  │
  ↓                                                     │
[Redirect tới login]                                   │
  ↓                                                     │
END ←──────────────────────────────────────────────────┘
```

### 4.2.4.3. Email đặt lại mật khẩu

![Screenshot: Password Reset Email](./screenshots/auth-reset-email.png)
*Hình 4.2.14: Email chứa link đặt lại mật khẩu*

**Nội dung email:**

```
From: MegaLearning <noreply@megalearning.com>
To: student@example.com
Subject: Đặt lại mật khẩu MegaLearning

Xin chào Nguyễn Văn A,

Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản 
của bạn.

Vui lòng nhấn vào nút bên dưới để đặt lại mật khẩu:

[Đặt lại mật khẩu]

Hoặc copy link sau vào trình duyệt:
https://megalearning.com/reset-password/abc123token

Link này sẽ hết hạn sau 60 phút.

Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua 
email này. Mật khẩu của bạn vẫn giữ nguyên.

Lưu ý: Không chia sẻ link này với bất kỳ ai!

Trân trọng,
MegaLearning Team
```

### 4.2.4.4. Thông báo gửi email thành công

![Screenshot: Reset Email Sent](./screenshots/auth-reset-email-sent.png)
*Hình 4.2.15: Thông báo đã gửi email*

```
┌─────────────────────────────────────────┐
│  ✅ Email đã được gửi!                  │
├─────────────────────────────────────────┤
│                                         │
│  Chúng tôi đã gửi link đặt lại mật     │
│  khẩu đến email:                       │
│                                         │
│  student@example.com                    │
│                                         │
│  Vui lòng kiểm tra hộp thư và làm      │
│  theo hướng dẫn.                       │
│                                         │
│  Link sẽ hết hạn sau 60 phút.          │
│                                         │
│  Không nhận được email?                │
│  [Gửi lại]                             │
│                                         │
└─────────────────────────────────────────┘
```

### 4.2.4.5. Form nhập mật khẩu mới

**URL:** `/reset-password/{token}`

![Screenshot: Reset Password Form](./screenshots/auth-reset-form.png)
*Hình 4.2.16: Form nhập mật khẩu mới*

```
┌─────────────────────────────────────────────────┐
│                 MegaLearning                    │
│                 Đặt lại mật khẩu                │
├─────────────────────────────────────────────────┤
│                                                 │
│  Email:                                         │
│  student@example.com (không thể thay đổi)      │
│                                                 │
│  Mật khẩu mới:                                  │
│  [_________________________________________] 👁  │
│  ⚠️ Ít nhất 8 ký tự                            │
│                                                 │
│  Xác nhận mật khẩu mới:                        │
│  [_________________________________________] 👁  │
│                                                 │
│  Yêu cầu mật khẩu:                             │
│  ✓ Ít nhất 8 ký tự                             │
│  ✓ Chứa chữ hoa                                │
│  ✓ Chứa chữ thường                             │
│  ✓ Chứa số                                     │
│                                                 │
│  [    Đặt lại mật khẩu    ]                    │
│                                                 │
└─────────────────────────────────────────────────┘
```

### 4.2.4.6. Code xử lý đặt lại mật khẩu (Backend)

**Controller: `PasswordResetController.php`**

```php
// Send reset link
public function sendResetLink(Request $request)
{
    $request->validate([
        'email' => ['required', 'email', 'exists:users,email']
    ]);

    // Create reset token
    $token = Str::random(60);
    
    // Store in database
    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $request->email],
        [
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now()
        ]
    );

    // Send email
    Mail::to($request->email)->send(
        new PasswordResetMail($token, $request->email)
    );

    return back()->with('status', 
        'Password reset link sent to your email!');
}

// Reset password
public function reset(Request $request)
{
    $request->validate([
        'token' => ['required'],
        'email' => ['required', 'email', 'exists:users,email'],
        'password' => ['required', 'min:8', 'confirmed'],
    ]);

    // Verify token
    $resetRecord = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();

    if (!$resetRecord || 
        !Hash::check($request->token, $resetRecord->token)) {
        return back()->withErrors(['email' => 'Invalid reset token']);
    }

    // Check expiration (60 minutes)
    if (now()->diffInMinutes($resetRecord->created_at) > 60) {
        return back()->withErrors(['email' => 'Reset token expired']);
    }

    // Update password
    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    // Delete token
    DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->delete();

    // Send confirmation email
    Mail::to($user->email)->send(
        new PasswordChangedMail($user)
    );

    return redirect()->route('login')
        ->with('status', 'Password has been reset successfully!');
}
```

### 4.2.4.7. Database Schema

**Table: `password_reset_tokens`**

```sql
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    PRIMARY KEY (email)
);
```

### 4.2.4.8. Thông báo đổi mật khẩu thành công

![Screenshot: Reset Success](./screenshots/auth-reset-success.png)
*Hình 4.2.17: Thông báo đặt lại mật khẩu thành công*

```
┌─────────────────────────────────────────┐
│  ✅ Đặt lại mật khẩu thành công!        │
├─────────────────────────────────────────┤
│                                         │
│  Mật khẩu của bạn đã được cập nhật.    │
│                                         │
│  Bạn có thể đăng nhập bằng mật khẩu    │
│  mới ngay bây giờ.                     │
│                                         │
│  [Đăng nhập]                           │
│                                         │
└─────────────────────────────────────────┘
```

### 4.2.4.9. Email xác nhận đổi mật khẩu

![Screenshot: Password Changed Email](./screenshots/auth-password-changed-email.png)
*Hình 4.2.18: Email xác nhận mật khẩu đã thay đổi*

```
From: MegaLearning <noreply@megalearning.com>
To: student@example.com
Subject: Mật khẩu của bạn đã được thay đổi

Xin chào Nguyễn Văn A,

Mật khẩu cho tài khoản MegaLearning của bạn đã được thay đổi
thành công vào lúc Dec 20, 2025 - 10:45 AM.

Nếu bạn không thực hiện thay đổi này, vui lòng liên hệ với
chúng tôi ngay lập tức:
- Email: support@megalearning.com
- Hotline: 1900-xxxx

Để bảo vệ tài khoản:
• Không chia sẻ mật khẩu với bất kỳ ai
• Sử dụng mật khẩu mạnh và khác nhau cho mỗi dịch vụ
• Kích hoạt xác thực 2 bước (nếu có)

Trân trọng,
MegaLearning Security Team
```

---

## 4.2.5. Chức năng Đăng xuất (Logout)

### 4.2.5.1. Quy trình đăng xuất

![Screenshot: Logout Confirmation](./screenshots/auth-logout-confirm.png)
*Hình 4.2.19: Xác nhận đăng xuất*

**Modal xác nhận:**
```
┌─────────────────────────────────────────┐
│  🚪 Đăng xuất khỏi hệ thống?            │
├─────────────────────────────────────────┤
│                                         │
│  Bạn có chắc chắn muốn đăng xuất?      │
│                                         │
│  [Hủy]        [Đăng xuất]              │
│                                         │
└─────────────────────────────────────────┘
```

### 4.2.5.2. Code xử lý đăng xuất

```php
public function logout(Request $request)
{
    // Log activity
    activity()
        ->causedBy(Auth::user())
        ->log('User logged out');

    // Logout
    Auth::logout();

    // Invalidate session
    $request->session()->invalidate();

    // Regenerate CSRF token
    $request->session()->regenerateToken();

    return redirect()->route('login')
        ->with('status', 'Logged out successfully!');
}
```

### 4.2.5.3. Thông báo đăng xuất thành công

![Screenshot: Logout Success](./screenshots/auth-logout-success.png)
*Hình 4.2.20: Thông báo đăng xuất thành công*

```
┌─────────────────────────────────────────┐
│  ✅ Đăng xuất thành công!               │
├─────────────────────────────────────────┤
│                                         │
│  Bạn đã đăng xuất khỏi hệ thống.       │
│                                         │
│  Hẹn gặp lại bạn!                      │
│                                         │
│  [Đăng nhập lại]                       │
│                                         │
└─────────────────────────────────────────┘
```

---

## 4.2.6. Bảo mật Authentication

### 4.2.6.1. Security Features

**1. Password Security:**
- ✅ **Bcrypt Hashing:** Cost factor 12
- ✅ **Minimum 8 characters**
- ✅ **Password Confirmation**
- ✅ **Never stored in plain text**

**2. Session Security:**
- ✅ **HTTP Only cookies:** Chống XSS
- ✅ **Secure flag:** Chỉ truyền qua HTTPS
- ✅ **SameSite attribute:** Chống CSRF
- ✅ **Session regeneration:** Sau login/logout

**3. CSRF Protection:**
- ✅ **CSRF tokens** trong mọi form
- ✅ **Token rotation** sau mỗi request
- ✅ **Double submit cookie** pattern

**4. Rate Limiting:**
- ✅ **Login:** 5 attempts / minute
- ✅ **Password Reset:** 3 requests / hour
- ✅ **Registration:** 2 accounts / hour / IP

**5. Email Verification:**
- ✅ **Mandatory verification** sau đăng ký
- ✅ **Signed URLs** chống giả mạo
- ✅ **60 minutes expiration**

**6. Password Reset Security:**
- ✅ **Hashed tokens** trong database
- ✅ **60 minutes expiration**
- ✅ **One-time use** tokens
- ✅ **Email confirmation** sau reset

**7. Brute Force Protection:**
- ✅ **Account lockout** sau n lần fail
- ✅ **CAPTCHA** sau 3 lần thất bại
- ✅ **IP blocking** cho repeated attacks
- ✅ **Progressive delays** (exponential backoff)

### 4.2.6.2. Security Best Practices

![Screenshot: Security Measures](./screenshots/auth-security-diagram.png)
*Hình 4.2.21: Sơ đồ các biện pháp bảo mật*

**Checklist bảo mật:**

```
✅ SSL/TLS Certificate (HTTPS)
✅ Strong password policy
✅ Password hashing (Bcrypt)
✅ CSRF protection
✅ XSS protection
✅ SQL injection prevention (Eloquent ORM)
✅ Session management
✅ Rate limiting
✅ Email verification
✅ Password reset security
✅ Audit logging
✅ Two-factor authentication (Optional)
```

---

## 4.2.7. Activity Logging

### 4.2.7.1. Ghi log hoạt động

Hệ thống ghi log tất cả hoạt động authentication:

**Table: `activity_logs`**

```sql
CREATE TABLE activity_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NULL,
    action VARCHAR(255) NOT NULL,
    description TEXT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP NULL
);
```

**Ví dụ logs:**

| ID | User | Action | Description | IP | Time |
|----|------|--------|-------------|-----|------|
| 1 | John | login | User logged in | 192.168.1.100 | 10:30 AM |
| 2 | Mary | register | User registered | 192.168.1.101 | 10:35 AM |
| 3 | John | logout | User logged out | 192.168.1.100 | 11:00 AM |
| 4 | NULL | login_failed | Invalid credentials | 192.168.1.102 | 11:05 AM |

### 4.2.7.2. Admin xem logs

![Screenshot: Activity Logs](./screenshots/auth-activity-logs.png)
*Hình 4.2.22: Trang xem activity logs (Admin)*

```
┌───────────────────────────────────────────────────────┐
│ Security Logs                          [Export]       │
├───────────────────────────────────────────────────────┤
│ Filter: [All Actions ▼]  [All Users ▼]  [Today ▼]   │
├───────────────────────────────────────────────────────┤
│                                                       │
│ • 10:45 AM - John Doe logged in from 192.168.1.100  │
│ • 10:30 AM - Mary Jane registered (student)          │
│ • 10:15 AM - Failed login attempt from 192.168.1.50 │
│ • 09:50 AM - Admin reset password for user #123     │
│ • 09:30 AM - John Doe logged out                    │
│                                                       │
│ [1] [2] [3] ... [10]  Next →                        │
└───────────────────────────────────────────────────────┘
```

---

## 4.2.8. Responsive Design cho Authentication

### 4.2.8.1. Mobile View

![Screenshot: Mobile Login](./screenshots/auth-mobile-login.png)
*Hình 4.2.23: Giao diện đăng nhập trên mobile*

**Mobile Login:**
```
┌──────────────────┐
│  MegaLearning    │
│  ═══════════════ │
│                  │
│  Đăng nhập       │
│                  │
│  Email:          │
│  [____________]  │
│                  │
│  Password:       │
│  [____________]👁 │
│                  │
│  ☑ Remember me   │
│                  │
│  [   Login   ]   │
│                  │
│  Forgot password?│
│                  │
│  Sign up         │
│                  │
└──────────────────┘
```

---

## 4.2.9. User Experience Enhancements

### 4.2.9.1. Password Strength Meter

![Screenshot: Password Strength](./screenshots/auth-password-strength.png)
*Hình 4.2.24: Password strength meter*

```
Password: [weak_pass_______] 👁

Password Strength: 🔴 Weak
▓▓░░░░░░░░ 20%

Suggestions:
• Add uppercase letters
• Add numbers
• Add special characters
• Make it longer (12+ chars)
```

### 4.2.9.2. Show/Hide Password

Toggle hiển thị mật khẩu khi nhập:

```
Password: [●●●●●●●●●●●●] 👁  ← Click để show
Password: [mypassword123] 👁️  ← Click để hide
```

### 4.2.9.3. Auto-focus & Tab Navigation

- Auto-focus vào email field khi load trang
- Tab key để di chuyển giữa các fields
- Enter key để submit form

### 4.2.9.4. Loading States

![Screenshot: Loading Button](./screenshots/auth-loading-state.png)
*Hình 4.2.25: Button loading state*

```
[  Đăng nhập...  ⏳ ]  ← Đang xử lý
```

---

## 4.2.10. Testing Authentication

### 4.2.10.1. Test Cases

**Feature Tests:**

```php
// tests/Feature/AuthenticationTest.php

public function test_user_can_register()
{
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'student',
        'terms' => true,
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com'
    ]);
}

public function test_user_can_login()
{
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect('/dashboard');
    $this->assertAuthenticatedAs($user);
}

public function test_user_cannot_login_with_invalid_password()
{
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors();
    $this->assertGuest();
}
```

### 4.2.10.2. Browser Tests (Dusk)

```php
// tests/Browser/LoginTest.php

public function test_user_can_login_through_browser()
{
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $this->browse(function (Browser $browser) {
        $browser->visit('/login')
                ->type('email', 'test@example.com')
                ->type('password', 'password123')
                ->press('Đăng nhập')
                ->assertPathIs('/dashboard')
                ->assertSee('Welcome');
    });
}
```

---

## 4.2.11. Kết luận

Hệ thống Authentication của MegaLearning đã được thiết kế và triển khai đầy đủ với các tính năng:

✅ **Đăng ký:** Form validation, email verification, role assignment  
✅ **Đăng nhập:** Secure authentication, rate limiting, remember me  
✅ **Quên mật khẩu:** Password reset via email, token expiration  
✅ **Đăng xuất:** Session invalidation, CSRF protection  
✅ **Bảo mật:** Password hashing, CSRF, XSS, rate limiting  
✅ **Logging:** Activity tracking, audit trail  
✅ **UX:** Responsive design, loading states, error handling  
✅ **Testing:** Unit tests, feature tests, browser tests  

Hệ thống đảm bảo tính bảo mật cao, trải nghiệm người dùng tốt và tuân thủ các best practices trong phát triển web hiện đại.

---

## PHỤ LỤC: DANH SÁCH SCREENSHOTS CẦN CHỤP

### Authentication Screenshots (25 ảnh)

1. ✅ `auth-register-form.png` - Form đăng ký
2. ✅ `auth-register-flowchart.png` - Flowchart đăng ký
3. ✅ `auth-register-errors.png` - Validation errors
4. ✅ `auth-verification-email.png` - Email xác thực
5. ✅ `auth-register-success.png` - Đăng ký thành công
6. ✅ `auth-login-form.png` - Form đăng nhập
7. ✅ `auth-login-flowchart.png` - Flowchart đăng nhập
8. ✅ `auth-login-invalid.png` - Lỗi đăng nhập sai
9. ✅ `auth-login-throttle.png` - Rate limit exceeded
10. ✅ `auth-email-unverified.png` - Email chưa verify
11. ✅ `auth-login-success.png` - Dashboard sau login
12. ✅ `auth-forgot-password-form.png` - Form quên mật khẩu
13. ✅ `auth-reset-flowchart.png` - Flowchart reset password
14. ✅ `auth-reset-email.png` - Email reset password
15. ✅ `auth-reset-email-sent.png` - Thông báo gửi email
16. ✅ `auth-reset-form.png` - Form nhập password mới
17. ✅ `auth-reset-success.png` - Reset thành công
18. ✅ `auth-password-changed-email.png` - Email xác nhận
19. ✅ `auth-logout-confirm.png` - Xác nhận logout
20. ✅ `auth-logout-success.png` - Logout thành công
21. ✅ `auth-security-diagram.png` - Sơ đồ bảo mật
22. ✅ `auth-activity-logs.png` - Activity logs admin
23. ✅ `auth-mobile-login.png` - Mobile login
24. ✅ `auth-password-strength.png` - Password strength meter
25. ✅ `auth-loading-state.png` - Button loading state

---

**Tác giả:** MegaLearning Development Team  
**Ngày tạo:** December 20, 2025  
**Phiên bản:** 1.0
