<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - MegaLearning</title>
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
        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(216, 74, 92, 0.15) 0%, transparent 70%);
            top: -200px;
            right: -200px;
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(139, 53, 88, 0.1) 0%, transparent 70%);
            bottom: -150px;
            left: -150px;
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
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
        .login-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            overflow-y: auto;
            overflow-x: hidden;
        }
        .login-container::-webkit-scrollbar {
            display: none;
        }
        .login-container {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body>
    <div class="login-container relative z-10">
    <!--📌 Demo: admin@megalearning.com / teacher@megalearning.com / student@megalearning.com | password-->
    
    <div class="w-full max-w-md relative z-10">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                {{-- 🎨 THAY LOGO TẠI ĐÂY: --}}
                {{-- Option 1: File trong public/images/ (Khuyến nghị) --}}
                <img src="{{ asset('images/logo.svg') }}" alt="MegaLearning" class="w-20 h-20 drop-shadow-2xl">
                
                {{-- Option 2: Nếu dùng PNG thay vì SVG --}}
                {{-- <img src="{{ asset('images/logo.png') }}" alt="MegaLearning" class="w-20 h-20 drop-shadow-2xl"> --}}
                
                {{-- Option 3: URL từ internet --}}
                {{-- <img src="https://yourwebsite.com/logo.png" alt="MegaLearning" class="w-20 h-20 drop-shadow-2xl"> --}}
                
                {{-- Option 4: Base64 image (inline) --}}
                {{-- <img src="data:image/png;base64,iVBORw0KG..." alt="MegaLearning" class="w-20 h-20 drop-shadow-2xl"> --}}
            </div>
            <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-[#D84A5C] to-[#E08976] mb-2">
                MegaLearning
            </h1>
            <p class="text-gray-400 text-sm">Learn. Practice. Grow.</p>
        </div>

        <!-- Login Card -->
        <div class="glass-card rounded-3xl p-8 mb-6">
            <h2 class="text-2xl font-bold text-white mb-2">Chào mừng trở lại! 👋</h2>
            <p class="text-gray-400 text-sm mb-6">Đăng nhập để tiếp tục học tập</p>

            @if ($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/30 rounded-xl p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-red-300 text-sm font-medium">{{ $errors->first() }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Role Selection -->
                <div>
                    <label for="role" class="block text-sm font-semibold text-gray-300 mb-2">Chọn loại tài khoản</label>
                    <select 
                        name="role" 
                        id="role"
                        class="w-full px-4 py-3.5 bg-white/5 border border-white/10 text-white rounded-xl focus:outline-none focus:border-[#D84A5C] input-glow transition duration-200 appearance-none cursor-pointer"
                        style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27rgba(216,74,92,1)%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.2em;"
                    >
                        <option value="student">🎓 Học sinh</option>
                        <option value="teacher">👨‍🏫 Giáo viên</option>
                        <option value="admin">👑 Quản trị viên</option>
                    </select>
                </div>

                <!-- Email/Username -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-300 mb-2">Tên đăng nhập hoặc email</label>
                    <input 
                        type="text" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-3.5 bg-white/5 border border-white/10 text-white placeholder-gray-500 rounded-xl focus:outline-none focus:border-[#D84A5C] input-glow transition duration-200 @error('email') border-red-500 @enderror"
                        placeholder="admin@megalearning.com"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
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
                            onclick="togglePassword()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#D84A5C] transition"
                        >
                            <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/20 bg-white/5 text-[#D84A5C] focus:ring-[#D84A5C] focus:ring-offset-0 cursor-pointer">
                        <span class="ml-2 text-gray-400 group-hover:text-gray-300 transition">Ghi nhớ đăng nhập</span>
                    </label>
                    <a href="#" class="text-[#E08976] hover:text-[#D84A5C] font-medium transition">Quên mật khẩu?</a>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-[#D84A5C] to-[#E08976] text-white font-bold py-4 px-6 rounded-xl hover:shadow-lg hover:shadow-[#D84A5C]/50 focus:outline-none focus:ring-2 focus:ring-[#D84A5C] focus:ring-offset-2 focus:ring-offset-[#0F1729] transform transition hover:scale-[1.02] active:scale-[0.98]"
                >
                    Đăng nhập
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-white/10"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-transparent text-gray-500">hoặc</span>
                </div>
            </div>

            <!-- Register Link -->
            <p class="text-center text-sm text-gray-400">
                Chưa có tài khoản? 
                <a href="{{ route('register') }}" class="text-[#E08976] hover:text-[#D84A5C] font-semibold transition">Đăng ký ngay</a>
            </p>
        </div>

        <!-- Demo Accounts -->
    

        <!-- Footer -->
        <p class="text-center text-xs text-gray-600 mt-8">
            © 2025 MegaLearning. All rights reserved.
        </p>
    </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            
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
