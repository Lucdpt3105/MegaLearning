@extends('layouts.auth', ['title' => 'Đăng ký Người dùng'])

@section('content')
<div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur p-8 shadow-2xl">
  <div class="flex items-center gap-4 mb-6">
    <img src="{{ asset('images/mega-logo.svg') }}" class="h-9 w-9" alt="">
    <div>
      <h1 class="text-2xl md:text-3xl font-semibold">Tạo tài khoản mới ✨</h1>
      <p class="text-sm md:text-base text-slate-400">Đăng ký để bắt đầu học trên MegaLearning.</p>
    </div>
  </div>

  @if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
      @foreach ($errors->all() as $err) • {{ $err }}<br> @endforeach
    </div>
  @endif

  <form method="POST" action="{{ route('register') }}"
        x-data="{show1:false,show2:false,caps1:false,caps2:false,loading:false}"
        @submit="loading=true" novalidate class="space-y-5">
    @csrf

    <div>
      <label for="name" class="text-base text-slate-300">Họ tên (tuỳ chọn)</label>
      <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Nguyễn Văn A"
             class="mt-2 w-full rounded-2xl bg-slate-900/60 border border-white/10 text-slate-100 placeholder-slate-500
                    focus:border-emerald-400 focus:ring-0 px-4 py-3 text-base">
      @error('name') <p class="mt-2 text-xs text-red-300">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="email" class="text-base text-slate-300">Email</label>
      <input id="email" name="email" type="email" value="{{ old('email') }}" required
             class="mt-2 w-full rounded-2xl bg-slate-900/60 border border-white/10 text-slate-100 placeholder-slate-500
                    focus:border-emerald-400 focus:ring-0 px-4 py-3 text-base">
      @error('email') <p class="mt-2 text-xs text-red-300">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="password" class="text-base text-slate-300">Mật khẩu</label>
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
      <div class="mt-2 flex items-center justify-between text-xs">
        <span x-show="caps1" class="text-amber-300">Caps Lock đang bật</span>
        {{-- Meter đơn giản theo độ dài --}}
        <template x-if="$root.querySelector('#password')?.value?.length">
          <span class="text-slate-400">
            Độ mạnh:
            <span :class="[
              ($root.querySelector('#password').value.length>=10 ? 'text-emerald-300' :
               $root.querySelector('#password').value.length>=6 ? 'text-yellow-300' : 'text-red-300')
            ]"
            x-text="$root.querySelector('#password').value.length>=10 ? 'Mạnh' :
                    ($root.querySelector('#password').value.length>=6 ? 'Trung bình' : 'Yếu')"></span>
          </span>
        </template>
      </div>
      @error('password') <p class="mt-2 text-xs text-red-300">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="password_confirmation" class="text-base text-slate-300">Nhập lại mật khẩu</label>
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
      <span x-show="!loading">Đăng ký</span>
      <span x-show="loading">Đang xử lý…</span>
    </button>

    <p class="text-center text-sm text-slate-400 mt-1">
      Đã có tài khoản? <a href="{{ route('login') }}" class="underline hover:text-slate-200">Đăng nhập</a>
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
