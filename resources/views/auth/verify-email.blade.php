<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực Email - MegaLearning</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            width: 100vw;
            background: linear-gradient(135deg, #4C3575 0%, #5849A6 50%, #6B9FD9 100%);
        }
        
        html::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(45deg, rgba(76,53,117,0.4), rgba(88,73,166,0.3), rgba(107,159,217,0.4), rgba(89,196,224,0.3));
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            z-index: 0;
            pointer-events: none;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 20px 60px rgba(76, 53, 117, 0.3);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #4C3575, #5849A6, #6B9FD9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md relative z-10">
        <div class="glass-card rounded-3xl p-10 text-center">
            <!-- Email Icon -->
            <div class="mx-auto w-20 h-20 bg-gradient-to-br from-[#4C3575] to-[#6B9FD9] rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>

            <h2 class="text-2xl font-bold gradient-text mb-3">Xác thực Email</h2>
            
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 rounded-xl p-3">
                    <p class="text-green-700 text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <p class="text-gray-600 mb-2">
                Chúng tôi đã gửi email xác thực đến địa chỉ email của bạn.
            </p>
            <p class="text-gray-500 text-sm mb-6">
                Vui lòng kiểm tra hộp thư (bao gồm cả thư rác) và nhấn vào link xác thực để hoàn tất đăng ký.
            </p>

            @if (session('resent'))
                <div class="mb-4 bg-blue-50 border border-blue-200 rounded-xl p-3">
                    <p class="text-blue-700 text-sm">✅ Đã gửi lại email xác thực!</p>
                </div>
            @endif

            <!-- Resend Button -->
            <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                @csrf
                <button type="submit" class="w-full bg-gradient-to-r from-[#4C3575] via-[#5849A6] to-[#6B9FD9] text-white font-bold py-3.5 px-6 rounded-xl hover:shadow-xl hover:shadow-[#5849A6]/40 focus:outline-none focus:ring-2 focus:ring-[#5849A6] focus:ring-offset-2 transform transition hover:scale-[1.02] active:scale-[0.98]">
                    📧 Gửi lại email xác thực
                </button>
            </form>

            <a href="{{ route('login') }}" class="text-sm text-[#5849A6] hover:text-[#4C3575] font-semibold transition">
                ← Quay lại đăng nhập
            </a>
        </div>

        <p class="text-center text-xs text-white/80 mt-8">
            © 2025 MegaLearning. All rights reserved.
        </p>
    </div>
</body>
</html>
