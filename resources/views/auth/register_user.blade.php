@extends('layouts.auth', ['title' => 'Đăng ký Người dùng'])

@section('content')
<div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur p-6 shadow-2xl">
  <div class="flex items-center gap-3 mb-5">
    <div class="size-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-lime-400 grid place-items-center shadow-lg">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
        <path d="M12 3v18M3 12h18" stroke="white" stroke-width="2.2" stroke-linecap="round"/>
      </svg>
    </div>
    <div>
      <h1 class="text-xl font-semibold">Tạo tài khoản mới ✨</h1>
      <p class="text-sm text-slate-400">Đăng ký để bắt đầu học trên MegaLearning.</p>
    </div>
  </div>

  @if ($errors->any())
    <div class="mb-3 rounded-lg border border-red-500/30 bg-red-500/10 px-3 py-2 text-sm text-red-200">
      @foreach ($errors->all() as $err) • {{ $err }}<br> @endforeach
    </div>
  @endif

  <form method="POST" action="{{ route('register.post') }}" x-data="{show1:false, show2:false, caps1:false, caps2:false, loading:false}"
        @submit="loading=true" novalidate class="space-y-4">
    @csrf

    <div>
      <label for="name" class="text-sm text-slate-300">Họ tên (tuỳ chọn)</label>
      <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Nguyễn Văn A"
             class="mt-1 w-full rounded-xl bg-slate-900/60 border border-white/10 text-slate-100 placeholder-slate-500 focus:border-slate-500 focus:ring-0">
      @error('name') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="email" class="text-sm text-slate-300">Email</label>
      <div class="relative mt-1">
        <input id="email" name="email" type="email" value="{{ old('email') }}" required
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
        <input :type="show1 ? 'text' : 'password'" id="password" name="password" required
               @keyup="caps1 = $event.getModifierState('CapsLock')"
               class="w-full rounded-xl bg-slate-900/60 border border-white/10 text-slate-100 focus:border-slate-500 focus:ring-0 pl-10 pr-16">
        <div class="absolute inset-y-0 left-0 grid place-items-center w-10 text-slate-400">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <rect x="5" y="11" width="14" height="9" rx="2" stroke="currentColor" stroke-width="1.6"/>
            <path d="M8 11V8a4 4 0 0 1 8 0v3" stroke="currentColor" stroke-width="1.6"/>
          </svg>
        </div>
        <button type="button" @click="show1=!show1"
                class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-slate-400 hover:text-slate-200">
          <span x-text="show1 ? 'Ẩn' : 'Hiện'"></span>
        </button>
      </div>
      <div class="mt-1 flex items-center justify-between">
        <div x-show="caps1" class="text-xs text-amber-300">Caps Lock đang bật</div>
        {{-- Meter đơn giản theo độ dài --}}
        <div x-data="{pw:''}" class="text-xs text-slate-400">
          <input type="hidden" x-model="pw" @input="pw = $event.target.value" x-bind:value="$root.querySelector('#password')?.value">
          <template x-if="$root.querySelector('#password')?.value?.length">
            <span>
              Độ mạnh:
              <span :class="[
                ($root.querySelector('#password').value.length>=10 ? 'text-emerald-300' :
                 $root.querySelector('#password').value.length>=6 ? 'text-yellow-300' : 'text-red-300')
              ]">
                <span x-text="$root.querySelector('#password').value.length>=10 ? 'Mạnh' :
                              ($root.querySelector('#password').value.length>=6 ? 'Trung bình' : 'Yếu')"></span>
              </span>
            </span>
          </template>
        </div>
      </div>
      @error('password') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="password_confirmation" class="text-sm text-slate-300">Nhập lại mật khẩu</label>
      <div class="relative mt-1">
        <input :type="show2 ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
               @keyup="caps2 = $event.getModifierState('CapsLock')"
               class="w-full rounded-xl bg-slate-900/60 border border-white/10 text-slate-100 focus:border-slate-500 focus:ring-0 pl-10 pr-16">
        <div class="absolute inset-y-0 left-0 grid place-items-center w-10 text-slate-400">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="M5 12l4 4L19 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <button type="button" @click="show2=!show2"
                class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-slate-400 hover:text-slate-200">
          <span x-text="show2 ? 'Ẩn' : 'Hiện'"></span>
        </button>
      </div>
      <div x-show="caps2" class="mt-1 text-xs text-amber-300">Caps Lock đang bật</div>
      @error('password_confirmation') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
    </div>

    <button :disabled="loading"
            class="w-full py-2 rounded-xl bg-gradient-to-tr from-emerald-600 to-lime-400 font-semibold shadow-lg hover:brightness-110 active:translate-y-px disabled:opacity-60 disabled:cursor-not-allowed">
      <span x-show="!loading">Đăng ký</span>
      <span x-show="loading">Đang xử lý…</span>
    </button>

    <p class="text-center text-xs text-slate-400">
      Đã có tài khoản? <a href="{{ route('login') }}" class="underline hover:text-slate-200">Đăng nhập</a>
    </p>
  </form>
</div>
@endsection
