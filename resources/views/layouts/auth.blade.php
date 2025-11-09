@php
    $title = $title ?? 'Đăng nhập';
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - MegaLearning</title>
    <style>
        :root {--bg:#0f172a;--card:#111827;--muted:#9ca3af;--text:#e5e7eb;--accent:#3b82f6;}
        *{box-sizing:border-box} html,body{height:100%}
        body{margin:0;font-family:system-ui,Segoe UI,Roboto,Helvetica,Arial;display:grid;place-items:center;background:linear-gradient(120deg,#0f172a,#1f2937);color:var(--text)}
        .card{width:min(480px,92vw);background:rgba(17,24,39,.85);backdrop-filter:saturate(140%) blur(6px);border:1px solid rgba(255,255,255,.06);border-radius:20px;padding:28px;box-shadow:0 10px 30px rgba(0,0,0,.4)}
        h1{margin:0 0 8px 0;font-size:24px}
        p.sub{margin:0 0 22px 0;color:var(--muted);font-size:14px}
        label{display:block;margin:10px 0 6px 6px;color:#cbd5e1;font-size:13px}
        input[type="email"], input[type="password"]{width:100%;padding:12px 14px;border-radius:12px;border:1px solid rgba(255,255,255,.1);background:#0b1220;color:var(--text)}
        .row{display:flex;align-items:center;justify-content:space-between;margin:12px 0}
        .btn{display:inline-block;width:100%;padding:12px 16px;border-radius:14px;border:0;background:var(--accent);color:white;font-weight:600;cursor:pointer}
        .muted-link{color:#93c5fd;text-decoration:none;font-size:13px}
        .error{background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.35);padding:10px;border-radius:12px;color:#fecaca;margin-bottom:12px;font-size:13px}
        .brand{display:flex;gap:10px;align-items:center;margin-bottom:18px}
        .badge{padding:4px 8px;border-radius:999px;border:1px solid rgba(255,255,255,.1);font-size:11px;color:#a5b4fc}
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">
            <div style="width:36px;height:36px;border-radius:8px;background:linear-gradient(135deg,#60a5fa,#6366f1);display:grid;place-items:center;font-weight:800;color:white;">ML</div>
            <div>
                <div style="font-weight:700;letter-spacing:.3px">MegaLearning</div>
                <div class="badge">{{ $title }}</div>
            </div>
        </div>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
