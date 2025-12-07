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
            background: linear-gradient(135deg, #4C3575 0%, #5849A6 50%, #6B9FD9 100%);
            position: fixed;
            top: 0;
            left: 0;
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
    
    <div class="w-full max-w-lg relative z-10">
    <!--📌 Demo: admin@megalearning.com / teacher@megalearning.com / student@megalearning.com | password-->
    
        <!-- Login Card -->
        <div class="glass-card rounded-3xl p-10 mb-6">
            <!-- Logo + Title in one line -->
            <div class="flex items-center gap-4 mb-6">
                <img src="{{ asset('images/logo.svg') }}" alt="MegaLearning" class="w-16 h-16 drop-shadow-lg shrink-0">
                <div>
                    <h2 class="text-3xl font-bold gradient-text">Đăng nhập</h2>
                    <p class="text-gray-600 text-sm mt-1">Chào mừng trở lại!</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-red-600 text-sm font-medium">{{ $errors->first() }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Role Selection -->
                <div>
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Chọn loại tài khoản</label>
                    <select 
                        name="role" 
                        id="role"
                        class="w-full px-4 py-3.5 bg-white border border-gray-200 text-gray-800 rounded-xl focus:outline-none input-glow transition duration-200 appearance-none cursor-pointer shadow-sm"
                        style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27rgba(88,73,166,1)%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.2em;"
                    >
                        <option value="student">🎓 Học sinh</option>
                        <option value="teacher">👨‍🏫 Giáo viên</option>
                        <option value="admin">👑 Quản trị viên</option>
                    </select>
                </div>

                <!-- Email/Username -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Tên đăng nhập hoặc email</label>
                    <input 
                        type="text" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-3.5 bg-white border border-gray-200 text-gray-800 placeholder-gray-400 rounded-xl focus:outline-none input-glow transition duration-200 shadow-sm @error('email') !border-red-500 @enderror"
                        placeholder="admin@megalearning.com"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Mật khẩu</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required
                            class="w-full px-4 py-3.5 bg-white border border-gray-200 text-gray-800 placeholder-gray-400 rounded-xl focus:outline-none input-glow transition duration-200 shadow-sm @error('password') !border-red-500 @enderror"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#5849A6] transition"
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
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 bg-white text-[#5849A6] focus:ring-[#5849A6] focus:ring-offset-0 cursor-pointer">
                        <span class="ml-2 text-gray-600 group-hover:text-gray-800 transition">Ghi nhớ đăng nhập</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-[#5849A6] hover:text-[#4C3575] font-medium transition">Quên mật khẩu?</a>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-linear-to-r from-[#4C3575] via-[#5849A6] to-[#6B9FD9] text-white font-bold py-4 px-6 rounded-xl hover:shadow-xl hover:shadow-[#5849A6]/40 focus:outline-none focus:ring-2 focus:ring-[#5849A6] focus:ring-offset-2 transform transition hover:scale-[1.02] active:scale-[0.98]"
                >
                    Đăng nhập
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">hoặc</span>
                </div>
            </div>

            <!-- Register Link -->
            <p class="text-center text-sm text-gray-600">
                Chưa có tài khoản? 
                <a href="{{ route('register') }}" class="text-[#5849A6] hover:text-[#4C3575] font-semibold transition">Đăng ký ngay</a>
            </p>
        </div>

        <!-- Demo Accounts -->
    

        <!-- Footer -->
        <p class="text-center text-xs text-white/80 mt-8">
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
