@extends('layouts.app')

@section('title', 'Student Home - MegaLearning')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- 🎓 Welcome Header with Illustration -->
        <div class="card-modern overflow-hidden">
            <div class="grid md:grid-cols-2 gap-8 p-8">
                <div class="flex flex-col justify-center space-y-6">
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-blue-600">Welcome back! 👋</p>
                        <h1 class="text-4xl font-bold text-gray-900">
                            Hi, {{ auth()->user()->name ?? 'Student' }}!
                        </h1>
                        <p class="text-lg text-gray-600">
                            Ready to continue your learning journey today?
                        </p>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="flex gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                <span class="text-xl">🎯</span>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">12</p>
                                <p class="text-xs text-gray-500">Active Courses</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-100 to-green-200 flex items-center justify-center">
                                <span class="text-xl">✨</span>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">89%</p>
                                <p class="text-xs text-gray-500">Avg Score</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Illustration -->
                <div class="hidden md:flex items-center justify-center">
                    <div class="relative w-full max-w-md">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-3xl"></div>
                        <svg class="relative w-full h-auto" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Student at desk illustration -->
                            <circle cx="200" cy="150" r="120" fill="#E0E7FF" opacity="0.5"/>
                            <rect x="150" y="180" width="100" height="80" rx="8" fill="#3A7BFF"/>
                            <circle cx="200" cy="120" r="40" fill="#FCD34D"/>
                            <rect x="180" y="100" width="40" height="20" rx="10" fill="#1F2937"/>
                            <path d="M160 200 L180 220 L220 220 L240 200" stroke="#fff" stroke-width="3" stroke-linecap="round"/>
                            <text x="200" y="280" text-anchor="middle" font-size="24" fill="#3A7BFF" font-weight="bold">Keep Learning!</text>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🚀 Quick Actions -->
        <div class="space-y-4">
            <div class="section-header">
                <h2 class="section-title">Quick Actions</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Take Exam -->
                <a href="/student/exams" class="quick-action group">
                    <div class="quick-action-icon bg-gradient-to-br from-blue-100 to-blue-200">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Take Exam</h3>
                    <p class="text-sm text-gray-500">Start your test now</p>
                </a>

                <!-- Schedule -->
                <a href="/student/schedule" class="quick-action group">
                    <div class="quick-action-icon bg-gradient-to-br from-purple-100 to-purple-200">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Schedule</h3>
                    <p class="text-sm text-gray-500">View your calendar</p>
                </a>

                <!-- Learning Progress -->
                <a href="/student/progress" class="quick-action group">
                    <div class="quick-action-icon bg-gradient-to-br from-green-100 to-green-200">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Progress</h3>
                    <p class="text-sm text-gray-500">Track achievements</p>
                </a>

                <!-- Forum -->
                <a href="/forum" class="quick-action group">
                    <div class="quick-action-icon bg-gradient-to-br from-orange-100 to-orange-200">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Forum</h3>
                    <p class="text-sm text-gray-500">Ask questions</p>
                </a>
            </div>
        </div>

        <!-- 📝 Upcoming Quizzes -->
        <div class="space-y-4">
            <div class="section-header">
                <h2 class="section-title">Upcoming Quizzes</h2>
                <span class="badge-modern badge-blue">3 Active</span>
            </div>

            <div class="space-y-3">
                <!-- Quiz Card 1 -->
                <div class="card-modern p-6 hover:scale-[1.01] transition-transform">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4 flex-1">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-lg">
                                M
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 text-lg">Mathematics Final Exam</h3>
                                <div class="flex items-center gap-4 mt-1">
                                    <span class="text-sm text-gray-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        60 minutes
                                    </span>
                                    <span class="text-sm text-gray-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        30 questions
                                    </span>
                                    <span class="badge-modern badge-orange">Due in 2 days</span>
                                </div>
                            </div>
                        </div>
                        <a href="/student/exams/1" class="btn-modern btn-primary">
                            Start Exam
                        </a>
                    </div>
                </div>

                <!-- Quiz Card 2 -->
                <div class="card-modern p-6 hover:scale-[1.01] transition-transform">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4 flex-1">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                P
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 text-lg">Physics Chapter 5 Quiz</h3>
                                <div class="flex items-center gap-4 mt-1">
                                    <span class="text-sm text-gray-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        30 minutes
                                    </span>
                                    <span class="text-sm text-gray-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        15 questions
                                    </span>
                                    <span class="badge-modern badge-green">Due in 5 days</span>
                                </div>
                            </div>
                        </div>
                        <a href="/student/exams/2" class="btn-modern btn-primary">
                            Start Exam
                        </a>
                    </div>
                </div>

                <!-- Quiz Card 3 -->
                <div class="card-modern p-6 hover:scale-[1.01] transition-transform">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4 flex-1">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white font-bold text-lg">
                                C
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 text-lg">Chemistry Lab Test</h3>
                                <div class="flex items-center gap-4 mt-1">
                                    <span class="text-sm text-gray-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        45 minutes
                                    </span>
                                    <span class="text-sm text-gray-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        20 questions
                                    </span>
                                    <span class="badge-modern badge-blue">Due in 1 week</span>
                                </div>
                            </div>
                        </div>
                        <a href="/student/exams/3" class="btn-modern btn-secondary">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📊 Learning Progress -->
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Current Streak -->
            <div class="card-modern p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-900">Learning Streak 🔥</h3>
                    <span class="text-3xl font-bold text-orange-600">7</span>
                </div>
                <p class="text-gray-600 text-sm mb-4">Keep it up! You've studied for 7 days straight.</p>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 70%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-2">3 more days to reach your goal!</p>
            </div>

            <!-- Recent Achievements -->
            <div class="card-modern p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Recent Badges 🏆</h3>
                <div class="flex gap-3">
                    <div class="flex-1 text-center p-3 bg-yellow-50 rounded-xl">
                        <span class="text-3xl">🥇</span>
                        <p class="text-xs text-gray-600 mt-1">Top Scorer</p>
                    </div>
                    <div class="flex-1 text-center p-3 bg-blue-50 rounded-xl">
                        <span class="text-3xl">📚</span>
                        <p class="text-xs text-gray-600 mt-1">Bookworm</p>
                    </div>
                    <div class="flex-1 text-center p-3 bg-green-50 rounded-xl">
                        <span class="text-3xl">⚡</span>
                        <p class="text-xs text-gray-600 mt-1">Fast Learner</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
