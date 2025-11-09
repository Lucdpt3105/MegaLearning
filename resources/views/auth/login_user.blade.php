@extends('layouts.auth', ['title' => 'Đăng nhập Người dùng'])

@section('content')
    <h1>Chào mừng trở lại 👋</h1>
    <p class="sub">Đăng nhập để tiếp tục học tập trên MegaLearning.</p>

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus />

        <label for="password">Mật khẩu</label>
        <input id="password" name="password" type="password" required />

        <div class="row">
            <label style="display:flex;align-items:center;gap:8px;font-size:13px;color:#cbd5e1;">
                <input type="checkbox" name="remember" style="accent-color:#3b82f6">
                Ghi nhớ đăng nhập
            </label>
            <a class="muted-link" href="#">Quên mật khẩu?</a>
        </div>

        <button class="btn" type="submit">Đăng nhập</button>
        <div style="text-align:center;margin-top:14px;font-size:13px;color:#9ca3af">
            Quản trị? <a class="muted-link" href="{{ route('admin.login') }}">Đăng nhập tại đây</a>
        </div>

        <div style="text-align:center;margin-top:14px;font-size:13px;color:#9ca3af">
    Chưa có tài khoản? <a class="muted-link" href="{{ route('register') }}">Đăng ký</a>
</div>
    </form>
@endsection
