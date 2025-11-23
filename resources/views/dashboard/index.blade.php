@extends('layouts.app')

@section('title', 'Dashboard - MegaLearning')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Welcome back, John! 👋</h1>
                <p class="text-purple-100 text-lg">Ready to continue your learning journey?</p>
            </div>
            <div class="hidden lg:block">
                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-6xl">
                    🎯
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="/chat" class="group bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
            <div class="flex items-center space-x-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-lg">Chat với AI</h3>
                    <p class="text-sm text-purple-100">Hỏi đáp với Gemini AI</p>
                </div>
                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        <a href="/quizzes" class="group bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
            <div class="flex items-center space-x-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-lg">Làm Quiz</h3>
                    <p class="text-sm text-green-100">Ôn tập kiến thức</p>
                </div>
                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        <a href="/courses" class="group bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
            <div class="flex items-center space-x-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-lg">Khóa học</h3>
                    <p class="text-sm text-orange-100">Khám phá kiến thức mới</p>
                </div>
                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @include('components.stat-card', [
            'title' => 'Total Courses',
            'value' => '24',
            'change' => '+12%',
            'icon' => '📚',
            'color' => 'blue'
        ])
        
        @include('components.stat-card', [
            'title' => 'Completed Quizzes',
            'value' => '156',
            'change' => '+23%',
            'icon' => '✅',
            'color' => 'green'
        ])
        
        @include('components.stat-card', [
            'title' => 'Average Score',
            'value' => '87%',
            'change' => '+5%',
            'icon' => '⭐',
            'color' => 'yellow'
        ])
        
        @include('components.stat-card', [
            'title' => 'Study Hours',
            'value' => '342',
            'change' => '+18%',
            'icon' => '⏱️',
            'color' => 'purple'
        ])
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Quizzes -->
        <div class="lg:col-span-2">
            @include('components.recent-quizzes')
        </div>

        <!-- Upcoming Events -->
        <div>
            @include('components.upcoming-events')
        </div>
    </div>

    <!-- Popular Courses -->
    @include('components.popular-courses')

    <!-- Performance Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @include('components.performance-chart')
        @include('components.leaderboard-widget')
    </div>
</div>
@endsection
