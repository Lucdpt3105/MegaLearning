<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Đăng nhập Người dùng</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="{{ asset('css/auth-admin.css') }}" rel="stylesheet">
</head>
<body>
  <div class="auth-wrap">
    <div class="auth-card">
      <div class="brand">
        <div class="logo">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M5 12l4 4L19 6" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <div>
          <h1 class="title">Chào mừng trở lại 👋</h1>
          <p class="sub">Đăng nhập để tiếp tục học tập trên MegaLearning.</p>
        </div>
      </div>

      {{-- Thông báo lỗi/ok --}}
      @if ($errors->any())
        <div class="alert error">
          @foreach ($errors->all() as $err)
            • {{ $err }}<br>
          @endforeach
        </div>
      @endif
      @if (session('status'))
        <div class="alert ok">{{ session('status') }}</div>
      @endif

      <form method="POST" action="{{ route('login.post') }}" novalidate>
        @csrf

        <label for="email">Email</label>
        <div class="input-wrap">
          <div class="icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M4 6h16v12H4z" stroke="currentColor" stroke-width="1.6" />
              <path d="M4 7l8 6 8-6" stroke="currentColor" stroke-width="1.6" fill="none"/>
            </svg>
          </div>
          <input id="email" name="email" type="email" class="input" value="{{ old('email') }}" required autofocus>
        </div>

        <label for="password">Mật khẩu</label>
        <div class="input-wrap">
          <div class="icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <rect x="5" y="11" width="14" height="9" rx="2" stroke="currentColor" stroke-width="1.6"/>
              <path d="M8 11V8a4 4 0 0 1 8 0v3" stroke="currentColor" stroke-width="1.6"/>
            </svg>
          </div>
          <input id="password" name="password" type="password" class="input" required>
          <button type="button" class="pw-toggle" onclick="
            const p=document.getElementById('password');
            p.type=p.type==='password'?'text':'password';
            this.innerText=p.type==='password'?'Hiện':'Ẩn';
          ">Hiện</button>
        </div>

        <div class="row">
          <label class="remember">
            <input type="checkbox" name="remember">
            Ghi nhớ đăng nhập
          </label>
          <a class="muted-link" href="#">Quên mật khẩu?</a>
        </div>

        <button class="btn" type="submit">Đăng nhập</button>

        <p class="note">
          Quản trị? <a class="muted-link" href="{{ route('admin.login') }}">Đăng nhập tại đây</a>
        </p>
        <p class="note">
          Chưa có tài khoản? <a class="muted-link" href="{{ route('register') }}">Đăng ký</a>
        </p>
      </form>
    </div>
  </div>
</body>
</html>
