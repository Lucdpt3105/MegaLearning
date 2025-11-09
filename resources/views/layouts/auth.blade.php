<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>{{ $title ?? 'MegaLearning Auth' }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen text-slate-100 bg-slate-950">
  <div class="min-h-screen grid md:grid-cols-2">
    {{-- FORM PANEL (bên trái) --}}
    <main class="flex items-center justify-center p-6 md:p-12 order-1">
      <div class="w-full max-w-xl"> {{-- tăng kích thước form --}}
        @yield('content')
      </div>
    </main>

    {{-- BRAND PANEL (bên phải, ẩn trên mobile) --}}
    <aside class="relative hidden md:flex flex-col justify-between p-10 overflow-hidden order-2">
      <div class="absolute inset-0 -z-10">
        <div class="absolute -top-24 -left-24 h-[520px] w-[520px] rounded-full bg-cyan-500/10 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-[520px] w-[520px] rounded-full bg-blue-600/10 blur-3xl"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-900/30 via-slate-900/40 to-blue-900/30"></div>
      </div>

      <div class="flex items-center gap-3">
        <img src="{{ asset('images/mega-logo.svg') }}" alt="MegaLearning" class="h-12 w-12">
        <div>
          <p class="text-lg font-semibold tracking-wide">MegaLearning</p>
          <p class="text-xs text-slate-400 -mt-1">Learn. Practice. Grow.</p>
        </div>
      </div>

      <div class="space-y-6">
        <h2 class="text-3xl font-semibold leading-tight">
          Nền tảng học tập<br> cho dự án của bạn
        </h2>
        <ul class="space-y-3 text-slate-300/90 text-base">
          <li class="flex items-start gap-3">
            <span class="mt-1 inline-flex size-6 items-center justify-center rounded-md bg-white/10">✓</span>
            Giao diện gọn, rõ ràng theo Tailwind v4 + Vite
          </li>
          <li class="flex items-start gap-3">
            <span class="mt-1 inline-flex size-6 items-center justify-center rounded-md bg-white/10">✓</span>
            Đăng nhập/Đăng ký trực quan, hỗ trợ Ẩn/Hiện mật khẩu
          </li>
          <li class="flex items-start gap-3">
            <span class="mt-1 inline-flex size-6 items-center justify-center rounded-md bg-white/10">✓</span>
            Bảo toàn session Laravel, phân quyền theo vai trò
          </li>
        </ul>
      </div>

      <p class="text-xs text-slate-500">© {{ date('Y') }} MegaLearning. All rights reserved.</p>
    </aside>
  </div>
</body>
</html>
