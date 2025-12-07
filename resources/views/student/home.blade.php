@extends('layouts.app')

@section('title', 'Student Home - MegaLearning')

@section('content')
<div class="min-h-screen bg-gray-50 py-10"> 
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <div class="grid lg:grid-cols-3 gap-6">
            
            <!-- Welcome Card with Image -->
            <div class="lg:col-span-2 relative overflow-hidden rounded-2xl shadow-xl h-64">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1200&q=80" 
                     alt="Students Learning" 
                     class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-900/90 via-purple-900/85 to-pink-900/80"></div>
                <div class="relative h-full flex items-center justify-between p-8">
                    <div class="max-w-2xl">
                        <p class="text-sm font-semibold text-indigo-200 uppercase tracking-wider mb-2">Welcome back!</p>
                        <h1 class="text-4xl font-extrabold text-white mb-3">
                            Hi, {{ auth()->user()->name ?? 'Student' }}!
                        </h1>
                        <p class="text-lg text-indigo-100 mb-6">
                            Let's dive into your active courses and upcoming tasks.
                        </p>
                        <a href="/student/courses" class="inline-flex items-center gap-2 px-6 py-3 bg-white hover:bg-gray-100 text-indigo-700 font-semibold rounded-xl transition shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Explore Courses
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-4">
                <!-- Active Courses Card -->
                <div class="relative overflow-hidden rounded-2xl shadow-lg h-32 bg-white border border-gray-200">
                    <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=600&q=80" 
                         alt="Books" 
                         class="absolute inset-0 w-full h-full object-cover opacity-15">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-blue-600/5"></div>
                    <div class="relative h-full flex items-center justify-between p-6">
                        <div>
                            <p class="text-xs text-gray-600 uppercase font-semibold mb-1">Active Courses</p>
                            <p class="text-3xl font-bold text-blue-600">12</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>
                    <div class="absolute bottom-2 right-2">
                        <span class="text-xs text-blue-600 font-semibold bg-blue-50 px-2 py-1 rounded">+3 this month</span>
                    </div>
                </div>

                <!-- Average Score Card -->
                <div class="relative overflow-hidden rounded-2xl shadow-lg h-32 bg-white border border-gray-200">
                    <img src="https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=600&q=80" 
                         alt="Success" 
                         class="absolute inset-0 w-full h-full object-cover opacity-15">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-green-600/5"></div>
                    <div class="relative h-full flex items-center justify-between p-6">
                        <div>
                            <p class="text-xs text-gray-600 uppercase font-semibold mb-1">Average Score</p>
                            <p class="text-3xl font-bold text-green-600">89%</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="absolute bottom-2 right-2">
                        <span class="text-xs text-green-600 font-semibold bg-green-50 px-2 py-1 rounded">↑ 2%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions with Images -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900">Quick Actions</h2>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4">
                <!-- Take Exam -->
                <a href="/student/exams" class="group relative overflow-hidden rounded-2xl shadow-lg h-40 hover:shadow-xl transition">
                    <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=600&q=80" 
                         alt="Exam" 
                         class="absolute inset-0 w-full h-full object-cover transition group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-900/90 via-blue-900/60 to-blue-900/30"></div>
                    <div class="relative h-full flex flex-col justify-end p-4 text-white">
                        <div class="bg-blue-500 w-12 h-12 rounded-xl flex items-center justify-center mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg">Take Exam</h3>
                        <p class="text-sm text-blue-100">Start your test</p>
                    </div>
                </a>

                <!-- Schedule -->
                <a href="/student/schedule" class="group relative overflow-hidden rounded-2xl shadow-lg h-40 hover:shadow-xl transition">
                    <img src="https://images.unsplash.com/photo-1506784983877-45594efa4cbe?w=600&q=80" 
                         alt="Schedule" 
                         class="absolute inset-0 w-full h-full object-cover transition group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-purple-900/90 via-purple-900/60 to-purple-900/30"></div>
                    <div class="relative h-full flex flex-col justify-end p-4 text-white">
                        <div class="bg-purple-500 w-12 h-12 rounded-xl flex items-center justify-center mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg">Schedule</h3>
                        <p class="text-sm text-purple-100">View calendar</p>
                    </div>
                </a>

                <!-- Progress -->
                <a href="/student/progress" class="group relative overflow-hidden rounded-2xl shadow-lg h-40 hover:shadow-xl transition">
                    <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&q=80" 
                         alt="Progress" 
                         class="absolute inset-0 w-full h-full object-cover transition group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-green-900/90 via-green-900/60 to-green-900/30"></div>
                    <div class="relative h-full flex flex-col justify-end p-4 text-white">
                        <div class="bg-green-500 w-12 h-12 rounded-xl flex items-center justify-center mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg">Progress</h3>
                        <p class="text-sm text-green-100">Track stats</p>
                    </div>
                </a>

                <!-- Forum -->
                <a href="/forum" class="group relative overflow-hidden rounded-2xl shadow-lg h-40 hover:shadow-xl transition">
                    <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?w=600&q=80" 
                         alt="Forum" 
                         class="absolute inset-0 w-full h-full object-cover transition group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-orange-900/90 via-orange-900/60 to-orange-900/30"></div>
                    <div class="relative h-full flex flex-col justify-end p-4 text-white">
                        <div class="bg-orange-500 w-12 h-12 rounded-xl flex items-center justify-center mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg">Forum</h3>
                        <p class="text-sm text-orange-100">Ask questions</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Upcoming Quizzes -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900">Upcoming Quizzes</h2>
                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-semibold rounded-full">3 Active</span>
            </div>

            <div class="space-y-4">
                <!-- Quiz Card 1 -->
                <div class="relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 hover:shadow-xl transition group">
                    <div class="absolute top-0 right-0 w-64 h-full">
                        <img src="https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=600&q=80" 
                             alt="Math" 
                             class="w-full h-full object-cover opacity-10 transition group-hover:scale-110">
                    </div>
                    <div class="relative flex items-center justify-between p-6">
                        <div class="flex items-center gap-4 flex-1">
                            <div class="bg-blue-500 text-white w-16 h-16 rounded-xl flex items-center justify-center text-2xl font-bold flex-shrink-0">
                                M
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 text-xl mb-2">Mathematics Final Exam</h3>
                                <div class="flex items-center gap-4 flex-wrap">
                                    <span class="flex items-center gap-1 text-sm text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        60 minutes
                                    </span>
                                    <span class="flex items-center gap-1 text-sm text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        30 questions
                                    </span>
                                    <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">Due in 2 days</span>
                                </div>
                            </div>
                        </div>
                        <a href="/student/exams/1" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition ml-4">
                            Start Exam
                        </a>
                    </div>
                </div>

                <!-- Quiz Card 2 -->
                <div class="relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 hover:shadow-xl transition group">
                    <div class="absolute top-0 right-0 w-64 h-full">
                        <img src="https://images.unsplash.com/photo-1636466497217-26a8cbeaf0aa?w=600&q=80" 
                             alt="Physics" 
                             class="w-full h-full object-cover opacity-10 transition group-hover:scale-110">
                    </div>
                    <div class="relative flex items-center justify-between p-6">
                        <div class="flex items-center gap-4 flex-1">
                            <div class="bg-purple-500 text-white w-16 h-16 rounded-xl flex items-center justify-center text-2xl font-bold flex-shrink-0">
                                P
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 text-xl mb-2">Physics Chapter 5 Quiz</h3>
                                <div class="flex items-center gap-4 flex-wrap">
                                    <span class="flex items-center gap-1 text-sm text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        30 minutes
                                    </span>
                                    <span class="flex items-center gap-1 text-sm text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        15 questions
                                    </span>
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Due in 5 days</span>
                                </div>
                            </div>
                        </div>
                        <a href="/student/exams/2" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition ml-4">
                            Start Exam
                        </a>
                    </div>
                </div>

                <!-- Quiz Card 3 -->
                <div class="relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 hover:shadow-xl transition group">
                    <div class="absolute top-0 right-0 w-64 h-full">
                        <img src="https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=600&q=80" 
                             alt="Chemistry" 
                             class="w-full h-full object-cover opacity-10 transition group-hover:scale-110">
                    </div>
                    <div class="relative flex items-center justify-between p-6">
                        <div class="flex items-center gap-4 flex-1">
                            <div class="bg-green-500 text-white w-16 h-16 rounded-xl flex items-center justify-center text-2xl font-bold flex-shrink-0">
                                C
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 text-xl mb-2">Chemistry Lab Test</h3>
                                <div class="flex items-center gap-4 flex-wrap">
                                    <span class="flex items-center gap-1 text-sm text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        45 minutes
                                    </span>
                                    <span class="flex items-center gap-1 text-sm text-gray-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        20 questions
                                    </span>
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Due in 1 week</span>
                                </div>
                            </div>
                        </div>
                        <a href="/student/exams/3" class="px-6 py-3 bg-white hover:bg-gray-50 text-green-600 font-semibold rounded-xl transition border-2 border-green-600 ml-4">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Learning Stats and Badges -->
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Learning Streak -->
            <div class="relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=600&q=80" 
                     alt="Learning" 
                     class="absolute inset-0 w-full h-full object-cover opacity-10">
                <div class="relative p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-orange-100 rounded-full p-3">
                                <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 7h-6v13h-2v-6h-2v6H9V9H3V7h18v2z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 text-lg">Learning Streak</h3>
                        </div>
                        <div class="text-right">
                            <p class="text-4xl font-extrabold text-orange-600">7</p>
                            <p class="text-sm text-gray-500">days</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Keep it up! You've studied for 7 days straight.</p>
                    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-3 bg-gradient-to-r from-orange-500 to-red-500 rounded-full transition-all duration-500" style="width: 70%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">3 more days to reach your goal!</p>
                </div>
            </div>

            <!-- Recent Badges -->
            <div class="relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200">
                <img src="https://images.unsplash.com/photo-1567427017947-545c5f8d16ad?w=600&q=80" 
                     alt="Achievements" 
                     class="absolute inset-0 w-full h-full object-cover opacity-10">
                <div class="relative p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-yellow-100 rounded-full p-3">
                            <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg">Recent Badges</h3>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 text-center hover:shadow-lg transition group">
                            <img src="https://images.unsplash.com/photo-1552566626-52f8b828add9?w=200&q=80" 
                                 alt="Top Scorer" 
                                 class="absolute inset-0 w-full h-full object-cover opacity-20 transition group-hover:scale-110">
                            <div class="relative">
                                <div class="bg-yellow-500 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-700 font-semibold">Top Scorer</p>
                            </div>
                        </div>
                        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 p-4 text-center hover:shadow-lg transition group">
                            <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=200&q=80" 
                                 alt="Bookworm" 
                                 class="absolute inset-0 w-full h-full object-cover opacity-20 transition group-hover:scale-110">
                            <div class="relative">
                                <div class="bg-blue-500 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-700 font-semibold">Bookworm</p>
                            </div>
                        </div>
                        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-green-50 to-green-100 p-4 text-center hover:shadow-lg transition group">
                            <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=200&q=80" 
                                 alt="Fast Learner" 
                                 class="absolute inset-0 w-full h-full object-cover opacity-20 transition group-hover:scale-110">
                            <div class="relative">
                                <div class="bg-green-500 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M13 2.03v2.02c4.39.54 7.5 4.53 6.96 8.92-.46 3.64-3.32 6.53-6.96 6.96v2c5.5-.55 9.5-5.43 8.95-10.93-.45-4.75-4.22-8.5-8.95-8.97zM11 2.03C6.61 2.57 3.5 6.56 4.04 11c.46 3.64 3.32 6.53 6.96 6.96v-2c-3.64-.46-6.53-3.32-6.96-6.96C3.5 6.61 6.61 2.57 11 2.03V2.03z"/>
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-700 font-semibold">Fast Learner</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection