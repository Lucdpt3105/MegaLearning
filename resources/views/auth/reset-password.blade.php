<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - MegaLearning</title>
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
                <div class="w-20 h-20 bg-linear-to-br from-[#5849A6] to-[#6B9FD9] rounded-2xl mx-auto mb-4 flex items-center justify-center shadow-xl transform -rotate-3">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold gradient-text">Đặt lại mật khẩu</h1>
                <p class="text-gray-600 mt-2">Nhập mật khẩu mới của bạn</p>
            </div>

            <!-- Form -->
            <div>
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <!-- Email (readonly) -->
                    <div class="mb-6">
                        <label for="email_display" class="block text-sm font-semibold text-gray-700 mb-2">
                            Địa chỉ Email
                        </label>
                        <input type="email" 
                               id="email_display"
                               value="{{ $email }}"
                               readonly
                               class="w-full px-4 py-3.5 border border-gray-200 rounded-xl bg-gray-50 text-gray-600 shadow-sm">
                    </div>

                    <!-- New Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Mật khẩu mới <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               required
                               placeholder="Nhập mật khẩu mới"
                               class="w-full px-4 py-3.5 bg-white border border-gray-200 text-gray-800 placeholder-gray-400 rounded-xl input-glow focus:outline-none transition shadow-sm">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            Xác nhận mật khẩu <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               required
                               placeholder="Xác nhận mật khẩu mới"
                               class="w-full px-4 py-3.5 bg-white border border-gray-200 text-gray-800 placeholder-gray-400 rounded-xl input-glow focus:outline-none transition shadow-sm">
                    </div>

                    @error('email')
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl">
                            {{ $message }}
                        </div>
                    @enderror

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-linear-to-r from-[#4C3575] via-[#5849A6] to-[#6B9FD9] text-white font-bold py-4 px-6 rounded-xl hover:shadow-xl hover:shadow-[#5849A6]/40 focus:outline-none focus:ring-2 focus:ring-[#5849A6] focus:ring-offset-2 transform transition hover:scale-[1.02] active:scale-[0.98]">
                        Đặt lại mật khẩu
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-white/80 mt-8 text-sm">
            © 2025 MegaLearning. All rights reserved.
        </p>
    </div>
</body>
</html>
