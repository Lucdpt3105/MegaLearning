<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Đăng ký Người dùng</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Dùng lại CSS của trang login -->
  <link href="{{ asset('css/auth-admin.css') }}" rel="stylesheet">
</head>
<body>
  <div class="auth-wrap">
    <div class="auth-card">
      <div class="brand">
        <div class="logo">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 3v18M3 12h18" stroke="white" stroke-width="2.2" stroke-linecap="round"/>
          </svg>
        </div>
        <div>
          <h1 class="title">Tạo tài khoản mới ✨</h1>
          <p class="sub">Đăng ký để bắt đầu học trên MegaLearning.</p>
        </div>
      </div>

      {{-- Thông báo lỗi / trạng thái --}}
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

      <form method="POST" action="{{ route('register.post') }}" novalidate>
        @csrf

        <label for="name">Họ tên (tuỳ chọn)</label>
        <div class="input-wrap">
          <div class="icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm6 7a6 6 0 0 0-12 0" stroke="currentColor" stroke-width="1.6" fill="none"/>
            </svg>
          </div>
          <input id="name" name="name" type="text" class="input" value="{{ old('name') }}" placeholder="Nguyễn Văn A">
        </div>

        <label for="email">Email</label>
        <div class="input-wrap">
          <div class="icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M4 6h16v12H4z" stroke="currentColor" stroke-width="1.6" />
              <path d="M4 7l8 6 8-6" stroke="currentColor" stroke-width="1.6" fill="none"/>
            </svg>
          </div>
          <input id="email" name="email" type="email" class="input" value="{{ old('email') }}" required>
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

        <label for="password_confirmation">Nhập lại mật khẩu</label>
        <div class="input-wrap">
          <div class="icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M5 12l4 4L19 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <input id="password_confirmation" name="password_confirmation" type="password" class="input" required>
          <button type="button" class="pw-toggle" onclick="
            const c=document.getElementById('password_confirmation');
            c.type=c.type==='password'?'text':'password';
            this.innerText=c.type==='password'?'Hiện':'Ẩn';
          ">Hiện</button>
        </div>

        <button class="btn" type="submit">Đăng ký</button>

        <p class="note">
          Đã có tài khoản? <a class="muted-link" href="{{ route('login') }}">Đăng nhập</a>
        </p>
      </form>
    </div>
  </div>
</body>
</html>
