@extends('layouts.auth', ['title' => 'Đặt lại mật khẩu'])

@section('content')
<div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur p-8 shadow-2xl">
  <div class="flex items-center gap-4 mb-6">
    <img src="{{ asset('images/mega-logo.svg') }}" class="h-9 w-9" alt="">
    <div>
      <h1 class="text-2xl md:text-3xl font-semibold">Đặt lại mật khẩu</h1>
      <p class="text-sm md:text-base text-slate-400">Nhập mật khẩu mới cho tài khoản của bạn.</p>
    </div>
  </div>

  @if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
      @foreach ($errors->all() as $err) • {{ $err }}<br> @endforeach
    </div>
  @endif

  <form method="POST" action="{{ route('password.update') }}"
        x-data="{show1:false,show2:false,caps1:false,caps2:false,loading:false}"
        @submit="loading=true" class="space-y-5">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email ?? request('email') }}">

    <div>
      <label for="password" class="text-base text-slate-300">Mật khẩu mới</label>
      <div class="relative mt-2">
        <input :type="show1 ? 'text' : 'password'" id="password" name="password" required
               @keyup="caps1=$event.getModifierState('CapsLock')"
               class="w-full rounded-2xl bg-slate-900/60 border border-white/10 text-slate-100
                      focus:border-emerald-400 focus:ring-0 pr-20 px-4 py-3 text-base">
        <button type="button" @click="show1=!show1"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-slate-300 hover:text-slate-100">
          <span x-text="show1 ? 'Ẩn' : 'Hiện'"></span>
        </button>
      </div>
      <div x-show="caps1" class="mt-2 text-xs text-amber-300">Caps Lock đang bật</div>
      @error('password') <p class="mt-2 text-xs text-red-300">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="password_confirmation" class="text-base text-slate-300">Xác nhận mật khẩu mới</label>
      <div class="relative mt-2">
        <input :type="show2 ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
               @keyup="caps2=$event.getModifierState('CapsLock')"
               class="w-full rounded-2xl bg-slate-900/60 border border-white/10 text-slate-100
                      focus:border-emerald-400 focus:ring-0 pr-20 px-4 py-3 text-base">
        <button type="button" @click="show2=!show2"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-slate-300 hover:text-slate-100">
          <span x-text="show2 ? 'Ẩn' : 'Hiện'"></span>
        </button>
      </div>
      <div x-show="caps2" class="mt-2 text-xs text-amber-300">Caps Lock đang bật</div>
      @error('password_confirmation') <p class="mt-2 text-xs text-red-300">{{ $message }}</p> @enderror
    </div>

    <button :disabled="loading"
            class="w-full py-3 rounded-2xl bg-gradient-to-tr from-emerald-600 to-lime-500
                   text-base font-semibold shadow-lg hover:brightness-110 active:translate-y-px
                   disabled:opacity-60 disabled:cursor-not-allowed">
      <span x-show="!loading">Cập nhật mật khẩu</span>
      <span x-show="loading">Đang xử lý…</span>
    </button>

    <p class="text-center text-sm text-slate-400 mt-1">
      Quay lại <a href="{{ route('login') }}" class="underline hover:text-slate-200">Đăng nhập</a>
    </p>
  </form>
</div>
@endsection
