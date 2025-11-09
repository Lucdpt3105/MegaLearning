@extends('layouts.auth', ['title' => 'Đăng nhập Người dùng'])

@section('content')
<div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur p-8 shadow-2xl">
  <div class="flex items-center gap-4 mb-6">
    <img src="{{ asset('images/mega-logo.svg') }}" class="h-9 w-9" alt="">
    <div>
      <h1 class="text-2xl md:text-3xl font-semibold">Chào mừng trở lại 👋</h1>
      <p class="text-sm md:text-base text-slate-400">Đăng nhập để tiếp tục học trên MegaLearning.</p>
    </div>
  </div>

  @if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
      @foreach ($errors->all() as $err) • {{ $err }}<br> @endforeach
    </div>
  @endif

  <form method="POST" action="{{ route('login.post') }}"
        x-data="{show:false,caps:false,loading:false}"
        @submit="loading=true" novalidate class="space-y-5">
    @csrf

    <div>
      <label for="email" class="text-base text-slate-300">Email</label>
      <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
             class="mt-2 w-full rounded-2xl bg-slate-900/60 border border-white/10 text-slate-100 placeholder-slate-500
                    focus:border-fuchsia-400 focus:ring-0 px-4 py-3 text-base">
      @error('email') <p class="mt-2 text-xs text-red-300">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="password" class="text-base text-slate-300">Mật khẩu</label>
      <div class="relative mt-2">
        <input :type="show ? 'text' : 'password'" id="password" name="password" required
               @keyup="caps=$event.getModifierState('CapsLock')"
               class="w-full rounded-2xl bg-slate-900/60 border border-white/10 text-slate-100
                      focus:border-fuchsia-400 focus:ring-0 pr-20 px-4 py-3 text-base">
        <button type="button" @click="show=!show"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-slate-300 hover:text-slate-100">
          <span x-text="show ? 'Ẩn' : 'Hiện'"></span>
        </button>
      </div>
      <div x-show="caps" class="mt-2 text-xs text-amber-300">Caps Lock đang bật</div>
      @error('password') <p class="mt-2 text-xs text-red-300">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center justify-between text-sm md:text-base">
      <label class="inline-flex items-center gap-2 text-slate-300">
        <input type="checkbox" name="remember" class="rounded border-white/10 bg-slate-900/60">
        Ghi nhớ đăng nhập
      </label>
      <a class="text-slate-400 hover:text-slate-200" href="{{ route('password.request') }}">
  Quên mật khẩu?
    </div>

    <button :disabled="loading"
            class="w-full py-3 rounded-2xl bg-gradient-to-tr from-indigo-600 to-fuchsia-500
                   text-base font-semibold shadow-lg hover:brightness-110 active:translate-y-px
                   disabled:opacity-60 disabled:cursor-not-allowed">
      <span x-show="!loading">Đăng nhập</span>
      <span x-show="loading">Đang xử lý…</span>
    </button>

    <p class="text-center text-sm text-slate-400 mt-1">
      Quản trị? <a href="{{ route('admin.login') }}" class="underline hover:text-slate-200">Đăng nhập tại đây</a>
    </p>
    <p class="text-center text-sm text-slate-400">
      Chưa có tài khoản? <a href="{{ route('register') }}" class="underline hover:text-slate-200">Đăng ký</a>
    </p>
  </form>
</div>
@if (session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
  <div class="alert alert-danger">
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif
@endsection
