{{-- resources/views/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Arial;margin:24px}
    .card{border:1px solid #ddd;border-radius:12px;padding:20px;max-width:720px}
    .row{margin-bottom:8px}
    .success{background:#e6ffed;border:1px solid #b7f5c0;padding:10px;border-radius:8px;margin-bottom:12px}
  </style>
</head>
<body>
  @if (session('success'))
    <div class="success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <h2>Xin chào, {{ auth()->user()->name ?? 'User' }}</h2>
    <div class="row"><b>Email:</b> {{ auth()->user()->email }}</div>
    <div class="row"><b>Role:</b> {{ auth()->user()->role ?? 'user' }}</div>
    <div class="row"><b>Status:</b> {{ auth()->user()->status ?? 'active' }}</div>

    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit">Đăng xuất</button>
    </form>
  </div>
</body>
</html>
