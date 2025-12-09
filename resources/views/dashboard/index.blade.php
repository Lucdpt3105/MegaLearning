@extends('layouts.app')

@section('title', 'Dashboard - MegaLearning')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 mb-1">Welcome back, John! 👋</h1>
                <p class="text-gray-600">Ready to continue your learning journey?</p>
            </div>
            <div class="hidden lg:block">
                <div class="w-20 h-20 bg-gradient-to-br from-primary-100 to-secondary-100 rounded-lg flex items-center justify-center text-4xl">
                    🎯
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="/chat" class="group bg-white border border-gray-200 hover:border-primary-300 rounded-lg p-5 transition-all">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center group-hover:bg-primary-100 transition-colors">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-medium text-gray-900">Chat với AI</h3>
                    <p class="text-sm text-gray-500">Hỏi đáp với Gemini AI</p>
                </div>
            </div>
        </a>

        <a href="/quizzes" class="group bg-white border border-gray-200 hover:border-secondary-300 rounded-lg p-5 transition-all">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-secondary-50 rounded-lg flex items-center justify-center group-hover:bg-secondary-100 transition-colors">
                    <svg class="w-6 h-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-medium text-gray-900">Làm Quiz</h3>
                    <p class="text-sm text-gray-500">Ôn tập kiến thức</p>
                </div>
            </div>
        </a>

        <a href="/courses" class="group bg-white border border-gray-200 hover:border-accent-300 rounded-lg p-5 transition-all">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-accent-50 rounded-lg flex items-center justify-center group-hover:bg-accent-100 transition-colors">
                    <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-medium text-gray-900">Khóa học</h3>
                    <p class="text-sm text-gray-500">Khám phá kiến thức mới</p>
                </div>
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
