<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>{{ $title ?? 'MegaLearning Auth' }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-950 to-black text-slate-100">
  <div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none">
    <div class="size-[680px] rounded-full bg-blue-600/10 blur-3xl absolute -top-20 -left-20"></div>
    <div class="size-[640px] rounded-full bg-fuchsia-600/10 blur-3xl absolute bottom-0 right-0"></div>
  </div>

  <main class="min-h-screen grid place-items-center p-4">
    <div class="w-full max-w-xl">
      @yield('content')
    </div>
  </main>
</body>
</html>
