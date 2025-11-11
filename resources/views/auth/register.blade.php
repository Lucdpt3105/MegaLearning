<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - MegaLearning</title>
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
        }
        
        /* Hide scrollbar */
        ::-webkit-scrollbar {
            display: none;
        }
        html {
            -ms-overflow-style: none;
            scrollbar-width: none;
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
            background-image: url('/images/background.avif');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.15;
            z-index: 1;
            pointer-events: none;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }
        
        .glass-card {
            background: rgba(26, 35, 50, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(216, 74, 92, 0.3);
        }
        select option {
            background: #1a2332;
            color: white;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <!-- 🖼️ Background Image -->
    <div class="bg-image"></div>
    
    <div class="w-full max-w-md relative z-10">
        <div class="glass-card rounded-3xl p-8 mb-6">
            <!-- Logo + Title in one line -->
            <div class="flex items-center gap-4 mb-6">
                <img src="{{ asset('images/logo.svg') }}" alt="MegaLearning" class="w-16 h-16 drop-shadow-2xl flex-shrink-0">
                <div>
                    <h2 class="text-2xl font-bold text-white">Đăng ký</h2>
                    <p class="text-gray-400 text-sm mt-1">Bắt đầu hành trình học tập của bạn.</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/30 rounded-xl p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-red-300 text-sm font-medium mb-1">Vui lòng kiểm tra lại thông tin:</p>
                            <ul class="text-red-400 text-sm space-y-1 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ url('/register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-300 mb-2">Họ và tên</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name') }}"
                        required
                        class="w-full px-4 py-3.5 bg-white/5 border border-white/10 text-white placeholder-gray-500 rounded-xl focus:outline-none focus:border-[#D84A5C] input-glow transition duration-200 @error('name') border-red-500 @enderror"
                        placeholder="Nguyễn Văn A"
                    >
                    @error('name')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-300 mb-2">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-3.5 bg-white/5 border border-white/10 text-white placeholder-gray-500 rounded-xl focus:outline-none focus:border-[#D84A5C] input-glow transition duration-200 @error('email') border-red-500 @enderror"
                        placeholder="student@example.com"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-300 mb-2">Mật khẩu</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required
                            class="w-full px-4 py-3.5 bg-white/5 border border-white/10 text-white placeholder-gray-500 rounded-xl focus:outline-none focus:border-[#D84A5C] input-glow transition duration-200 @error('password') border-red-500 @enderror"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('password')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#D84A5C] transition"
                        >
                            <svg id="eye-open-password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-closed-password" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Ít nhất 8 ký tự</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-300 mb-2">Xác nhận mật khẩu</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            required
                            class="w-full px-4 py-3.5 bg-white/5 border border-white/10 text-white placeholder-gray-500 rounded-xl focus:outline-none focus:border-[#D84A5C] input-glow transition duration-200"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('password_confirmation')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#D84A5C] transition"
                        >
                            <svg id="eye-open-password_confirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-closed-password_confirmation" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-[#D84A5C] to-[#E08976] text-white font-bold py-4 px-6 rounded-xl hover:shadow-lg hover:shadow-[#D84A5C]/50 focus:outline-none focus:ring-2 focus:ring-[#D84A5C] focus:ring-offset-2 focus:ring-offset-[#0F1729] transform transition hover:scale-[1.02] active:scale-[0.98]"
                >
                    Đăng ký
                </button>
            </form>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-white/10"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-[#1a2332] text-gray-500">hoặc</span>
                </div>
            </div>

            <p class="text-center text-sm text-gray-400">
                Đã có tài khoản? 
                <a href="{{ route('login') }}" class="text-[#E08976] hover:text-[#D84A5C] font-semibold transition">Đăng nhập ngay</a>
            </p>
        </div>

        <p class="text-center text-xs text-gray-600 mt-8">
            © 2025 MegaLearning. All rights reserved.
        </p>
    </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const eyeOpen = document.getElementById('eye-open-' + fieldId);
            const eyeClosed = document.getElementById('eye-closed-' + fieldId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
</body>
</html>