<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - MegaLearning</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            background: linear-gradient(135deg, #0F1729 0%, #1a2332 100%);
            position: fixed;
            top: 0;
            left: 0;
        }
        
        /* 🎨 Diagonal Stripes Background */
        html::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                45deg,
                rgba(216, 74, 92, 0.03),
                rgba(216, 74, 92, 0.03) 20px,
                rgba(224, 137, 118, 0.02) 20px,
                rgba(224, 137, 118, 0.02) 40px
            );
            z-index: 0;
            pointer-events: none;
        }
        
        /* 🌊 Subtle Floating Orbs */
        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(216, 74, 92, 0.08) 0%, transparent 70%);
            top: -200px;
            right: -200px;
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
            z-index: 0;
        }
        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(139, 53, 88, 0.06) 0%, transparent 70%);
            bottom: -150px;
            left: -150px;
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
            z-index: 0;
        }
        
        /* 🖼️ Background Image Overlay */
        .bg-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('/images/background.svg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.15;
            z-index: 1;
            pointer-events: none;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1) rotate(0deg); }
            50% { transform: translateY(-40px) scale(1.08) rotate(5deg); }
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 20px 60px rgba(76, 53, 117, 0.3);
        }
        
        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(88, 73, 166, 0.25);
            border-color: #5849A6;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #4C3575, #5849A6, #6B9FD9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="bg-image"></div>
    <div class="max-w-md w-full relative z-10">
        <!-- Card -->
        <div class="glass-card rounded-3xl p-10 mb-6">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-linear-to-br from-[#5849A6] to-[#6B9FD9] rounded-2xl mx-auto mb-4 flex items-center justify-center shadow-xl transform rotate-3">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold gradient-text">Quên mật khẩu?</h1>
                <p class="text-gray-600 mt-2">Không sao, chúng tôi sẽ gửi link đặt lại cho bạn</p>
            </div>

            <!-- Form -->
            <div>
                @if (session('status'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl" role="alert">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Địa chỉ Email
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}"
                               required
                               autofocus
                               placeholder="Nhập email của bạn"
                               class="w-full px-4 py-3.5 bg-white border border-gray-200 text-gray-800 placeholder-gray-400 rounded-xl input-glow focus:outline-none transition shadow-sm @error('email') border-red-500! @enderror">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-linear-to-r from-[#4C3575] via-[#5849A6] to-[#6B9FD9] text-white font-bold py-4 px-6 rounded-xl hover:shadow-xl hover:shadow-[#5849A6]/40 focus:outline-none focus:ring-2 focus:ring-[#5849A6] focus:ring-offset-2 transform transition hover:scale-[1.02] active:scale-[0.98]">
                        Gửi link đặt lại
                    </button>
                </form>

                <!-- Back to Login -->
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-[#5849A6] hover:text-[#4C3575] font-medium inline-flex items-center justify-center transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Quay lại đăng nhập
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-white/80 mt-8 text-sm">
            © 2025 MegaLearning. All rights reserved.
        </p>
    </div>
</body>
</html>
