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
