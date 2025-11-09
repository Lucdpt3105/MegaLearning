@extends('layouts.auth', ['title' => 'Đăng ký Người dùng'])

@section('content')
  <h1>Tạo tài khoản mới ✨</h1>
  <p class="sub">Đăng ký để bắt đầu học trên MegaLearning.</p>

  <form method="POST" action="{{ route('register.post') }}">
    @csrf

    <label for="name">Họ tên (tuỳ chọn)</label>
    <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Nguyễn Văn A" />

    <label for="email">Email</label>
    <input id="email" name="email" type="email" value="{{ old('email') }}" required />

    <label for="password">Mật khẩu</label>
    <input id="password" name="password" type="password" required />

    <label for="password_confirmation">Nhập lại mật khẩu</label>
    <input id="password_confirmation" name="password_confirmation" type="password" required />

    <div class="row" style="margin-top:14px">
      <a class="muted-link" href="{{ route('login') }}">Đã có tài khoản? Đăng nhập</a>
    </div>

    <button class="btn" type="submit" style="margin-top:10px;">Đăng ký</button>
  </form>
@endsection
