@extends('layouts.auth', ['title' => 'Đăng nhập Quản trị'])

@section('content')
<div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur p-6 shadow-2xl">
  <div class="flex items-center gap-3 mb-5">
    <div class="size-10 rounded-xl bg-gradient-to-tr from-blue-600 to-cyan-400 grid place-items-center shadow-lg">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
        <path d="M5 12l4 4L19 6" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>
    <div>
      <h1 class="text-xl font-semibold">Khu vực Quản trị</h1>
      <p class="text-sm text-slate-400">Chỉ dành cho quản trị viên hệ thống.</p>
    </div>
  </div>

  @if ($errors->any())
    <div class="mb-3 rounded-lg border border-red-500/30 bg-red-500/10 px-3 py-2 text-sm text-red-200">
      @foreach ($errors->all() as $err) • {{ $err }}<br> @endforeach
    </div>
  @endif
  @if (session('status'))
    <div class="mb-3 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-3 py-2 text-sm text-emerald-200">
      {{ session('status') }}
    </div>
  @endif

  <form method="POST" action="{{ route('admin.login.post') }}" x-data="{show:false, caps:false, loading:false}"
        @submit="loading=true" novalidate class="space-y-4">
    @csrf

    <div>
      <label for="email" class="text-sm text-slate-300">Email quản trị</label>
      <div class="relative mt-1">
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
               class="w-full rounded-xl bg-slate-900/60 border border-white/10 text-slate-100 placeholder-slate-500 focus:border-slate-500 focus:ring-0 pl-10">
        <div class="absolute inset-y-0 left-0 grid place-items-center w-10 text-slate-400">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="M4 6h16v12H4z" stroke="currentColor" stroke-width="1.6" />
            <path d="M4 7l8 6 8-6" stroke="currentColor" stroke-width="1.6" fill="none"/>
          </svg>
        </div>
      </div>
      @error('email') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="password" class="text-sm text-slate-300">Mật khẩu</label>
      <div class="relative mt-1">
        <input :type="show ? 'text' : 'password'" id="password" name="password" required
               @keyup="caps = $event.getModifierState('CapsLock')"
               class="w-full rounded-xl bg-slate-900/60 border border-white/10 text-slate-100 focus:border-slate-500 focus:ring-0 pl-10 pr-16">
        <div class="absolute inset-y-0 left-0 grid place-items-center w-10 text-slate-400">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <rect x="5" y="11" width="14" height="9" rx="2" stroke="currentColor" stroke-width="1.6"/>
            <path d="M8 11V8a4 4 0 0 1 8 0v3" stroke="currentColor" stroke-width="1.6"/>
          </svg>
        </div>
        <button type="button" @click="show=!show"
                class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-slate-400 hover:text-slate-200">
          <span x-text="show ? 'Ẩn' : 'Hiện'"></span>
        </button>
      </div>
      <div x-show="caps" class="mt-1 text-xs text-amber-300">Caps Lock đang bật</div>
      @error('password') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center justify-between text-sm">
      <label class="inline-flex items-center gap-2 text-slate-300">
        <input type="checkbox" name="remember" class="rounded border-white/10 bg-slate-900/60">
        Ghi nhớ đăng nhập
      </label>
      <a href="#" class="text-slate-400 hover:text-slate-200">Quên mật khẩu?</a>
    </div>

    <button :disabled="loading"
            class="w-full py-2 rounded-xl bg-gradient-to-tr from-blue-600 to-cyan-400 font-semibold shadow-lg hover:brightness-110 active:translate-y-px disabled:opacity-60 disabled:cursor-not-allowed">
      <span x-show="!loading">Đăng nhập quản trị</span>
      <span x-show="loading">Đang xử lý…</span>
    </button>

    <p class="text-center text-xs text-slate-400">
      Người dùng thường? <a href="{{ route('login') }}" class="underline hover:text-slate-200">Đăng nhập tại đây</a>
    </p>
  </form>
</div>
@endsection
