<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng ký - MegaLearning</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        window.__SUPABASE_URL__ = "{{ config('supabase.url') }}";
        window.__SUPABASE_ANON_KEY__ = "{{ config('supabase.anon_key') }}";
    </script>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            background: linear-gradient(135deg, #4C3575 0%, #5849A6 50%, #6B9FD9 100%);
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
        
        /* 🎨 Animated Gradient Overlay */
        html::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                45deg,
                rgba(76, 53, 117, 0.4),
                rgba(88, 73, 166, 0.3),
                rgba(107, 159, 217, 0.4),
                rgba(89, 196, 224, 0.3)
            );
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            z-index: 0;
            pointer-events: none;
        }
        
        /* 🌊 Floating Orbs with New Colors */
        body::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(89, 196, 224, 0.15) 0%, transparent 70%);
            top: -250px;
            right: -250px;
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
            z-index: 0;
        }
        body::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(88, 73, 166, 0.12) 0%, transparent 70%);
            bottom: -200px;
            left: -200px;
            border-radius: 50%;
            animation: float 10s ease-in-out infinite reverse;
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
            opacity: 0.08;
            z-index: 1;
            pointer-events: none;
        }
        
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1) rotate(0deg); }
            50% { transform: translateY(-40px) scale(1.08) rotate(5deg); }
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 20px 60px rgba(76, 53, 117, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.1);
        }
        
        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(88, 73, 166, 0.25);
            border-color: #5849A6;
        }
        
        select option {
            background: white;
            color: #1f2937;
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
    <!-- 🖼️ Background Image -->
    <div class="bg-image"></div>
    
    <div class="w-full max-w-md relative z-10">
        <div class="glass-card rounded-3xl p-8 mb-6">
            <!-- Logo + Title in one line -->
            <div class="flex items-center gap-4 mb-6">
                <img src="{{ asset('images/logo.svg') }}" alt="MegaLearning" class="w-16 h-16 drop-shadow-lg shrink-0">
                <div>
                    <h2 class="text-3xl font-bold gradient-text">Đăng ký</h2>
                    <p class="text-gray-600 text-sm mt-1">Bắt đầu hành trình học tập của bạn.</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-red-600 text-sm font-medium mb-1">Vui lòng kiểm tra lại thông tin:</p>
                            <ul class="text-red-500 text-sm space-y-1 list-disc list-inside">
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
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Họ và tên</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name') }}"
                        required
                        class="w-full px-4 py-3.5 bg-white border border-gray-200 text-gray-800 placeholder-gray-400 rounded-xl focus:outline-none input-glow transition duration-200 shadow-sm @error('name') border-red-500! @enderror"
                        placeholder="Nguyễn Văn A"
                    >
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-3.5 bg-white border border-gray-200 text-gray-800 placeholder-gray-400 rounded-xl focus:outline-none input-glow transition duration-200 shadow-sm @error('email') border-red-500! @enderror"
                        placeholder="student@example.com"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Mật khẩu</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required
                            class="w-full px-4 py-3.5 bg-white border border-gray-200 text-gray-800 placeholder-gray-400 rounded-xl focus:outline-none input-glow transition duration-200 shadow-sm @error('password') border-red-500! @enderror"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('password')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#5849A6] transition"
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
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Xác nhận mật khẩu</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            required
                            class="w-full px-4 py-3.5 bg-white border border-gray-200 text-gray-800 placeholder-gray-400 rounded-xl focus:outline-none input-glow transition duration-200 shadow-sm"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('password_confirmation')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#5849A6] transition"
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
                    class="w-full bg-linear-to-r from-[#4C3575] via-[#5849A6] to-[#6B9FD9] text-white font-bold py-4 px-6 rounded-xl hover:shadow-xl hover:shadow-[#5849A6]/40 focus:outline-none focus:ring-2 focus:ring-[#5849A6] focus:ring-offset-2 transform transition hover:scale-[1.02] active:scale-[0.98]"
                >
                    Đăng ký
                </button>
            </form>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">hoặc</span>
                </div>
            </div>

            <!-- Google Sign-Up Button -->
            <button 
                type="button"
                onclick="signInWithGoogle()"
                id="google-register-btn"
                class="w-full flex items-center justify-center gap-3 bg-white border border-gray-300 text-gray-700 font-semibold py-3.5 px-6 rounded-xl hover:bg-gray-50 hover:border-gray-400 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#5849A6] focus:ring-offset-2 transform transition hover:scale-[1.01] active:scale-[0.99]"
            >
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <span>Đăng ký bằng Google</span>
            </button>

            <div class="mt-4"></div>

            <p class="text-center text-sm text-gray-600">
                Đã có tài khoản? 
                <a href="{{ route('login') }}" class="text-[#5849A6] hover:text-[#4C3575] font-semibold transition">Đăng nhập ngay</a>
            </p>
        </div>

        <p class="text-center text-xs text-white/80 mt-8">
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