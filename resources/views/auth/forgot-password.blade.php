<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - MegaLearning</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-linear-to-br from-purple-50 via-pink-50 to-blue-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-linear-to-r from-purple-600 to-blue-600 p-8 text-center">
                <div class="w-16 h-16 bg-white rounded-full mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white">Forgot Password?</h1>
                <p class="text-purple-100 mt-2">No worries, we'll send you reset instructions</p>
            </div>

            <!-- Form -->
            <div class="p-8">
                @if (session('status'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" role="alert">
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
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}"
                               required
                               autofocus
                               placeholder="Enter your email"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-linear-to-r from-purple-600 to-blue-600 text-white font-semibold py-3 rounded-lg hover:from-purple-700 hover:to-blue-700 transform hover:scale-[1.02] transition-all duration-200 shadow-lg">
                        Send Reset Link
                    </button>
                </form>

                <!-- Back to Login -->
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium flex items-center justify-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Login
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-600 mt-8 text-sm">
            © 2025 MegaLearning. All rights reserved.
        </p>
    </div>
</body>
</html>
