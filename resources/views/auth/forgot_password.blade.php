@extends('layouts.auth', ['title' => 'Quên mật khẩu'])

@section('content')
<div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur p-8 shadow-2xl">
  <div class="flex items-center gap-4 mb-6">
    <img src="{{ asset('images/mega-logo.svg') }}" class="h-9 w-9" alt="">
    <div>
      <h1 class="text-2xl md:text-3xl font-semibold">Quên mật khẩu?</h1>
      <p class="text-sm md:text-base text-slate-400">Nhập email để nhận liên kết đặt lại mật khẩu.</p>
    </div>
  </div>

  @if (session('status'))
    <div class="mb-4 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
      {{ session('status') }}
    </div>
  @endif
  @if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200">
      @foreach ($errors->all() as $err) • {{ $err }}<br> @endforeach
    </div>
  @endif

  <form method="POST" action="{{ route('password.email') }}" x-data="{loading:false}" @submit="loading=true" class="space-y-5">
    @csrf
    <div>
      <label for="email" class="text-base text-slate-300">Email</label>
      <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
             class="mt-2 w-full rounded-2xl bg-slate-900/60 border border-white/10 text-slate-100 placeholder-slate-500
                    focus:border-cyan-400 focus:ring-0 px-4 py-3 text-base">
      @error('email') <p class="mt-2 text-xs text-red-300">{{ $message }}</p> @enderror
    </div>

    <button :disabled="loading"
            class="w-full py-3 rounded-2xl bg-gradient-to-tr from-cyan-500 to-blue-600
                   text-base font-semibold shadow-lg hover:brightness-110 active:translate-y-px
                   disabled:opacity-60 disabled:cursor-not-allowed">
      <span x-show="!loading">Gửi liên kết đặt lại</span>
      <span x-show="loading">Đang gửi…</span>
    </button>

    <p class="text-center text-sm text-slate-400">
      Nhớ mật khẩu rồi? <a href="{{ route('login') }}" class="underline hover:text-slate-200">Đăng nhập</a>
    </p>
  </form>
</div>
@endsection
