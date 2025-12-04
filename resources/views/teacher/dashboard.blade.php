@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- 🎓 Welcome Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Teacher Dashboard</h1>
                <p class="text-lg text-gray-600 mt-2">Welcome back, <span class="text-blue-600 font-semibold">{{ auth()->user()->name }}</span>! 🎓</p>
            </div>
            
            <!-- Search Bar -->
            <div class="hidden lg:block w-96">
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="Search for quizzes, courses, or topics..."
                        class="input-modern pl-12"
                    />
                    <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- 📊 Stats Cards with Modern Gradient -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Subjects Card -->
            <div class="card-gradient-blue group cursor-pointer hover:scale-105 hover:shadow-2xl transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="icon-box bg-white/20 shadow-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center text-4xl transform group-hover:scale-110 transition-transform">
                            📚
                        </div>
                    </div>
                    <div>
                        <p class="text-blue-100 text-sm font-medium mb-1">Subjects</p>
                        <h3 class="text-5xl font-bold mb-2">{{ $stats['subjects_count'] }}</h3>
                        <div class="flex items-center text-sm text-blue-100">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span>Active courses</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Topics Card -->
            <div class="card-gradient-purple group cursor-pointer hover:scale-105 hover:shadow-2xl transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="icon-box bg-white/20 shadow-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center text-4xl transform group-hover:scale-110 transition-transform">
                            📝
                        </div>
                    </div>
                    <div>
                        <p class="text-purple-100 text-sm font-medium mb-1">Topics</p>
                        <h3 class="text-5xl font-bold mb-2">{{ $stats['topics_count'] }}</h3>
                        <div class="flex items-center text-sm text-purple-100">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Learning modules</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions Card -->
            <div class="card-gradient-green group cursor-pointer hover:scale-105 hover:shadow-2xl transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="icon-box bg-white/20 shadow-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center text-4xl transform group-hover:scale-110 transition-transform">
                            ❓
                        </div>
                    </div>
                    <div>
                        <p class="text-green-100 text-sm font-medium mb-1">Questions</p>
                        <h3 class="text-5xl font-bold mb-2">{{ $stats['questions_count'] }}</h3>
                    <div class="flex items-center text-sm text-green-100">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>In question bank</span>
                    </div>
                </div>
            </div>
        </div>

            <!-- Exams Card -->
            <div class="card-gradient-orange group cursor-pointer hover:scale-105 hover:shadow-2xl transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="icon-box bg-white/20 shadow-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center text-4xl transform group-hover:scale-110 transition-transform">
                            📋
                        </div>
                    </div>
                    <div>
                        <p class="text-orange-100 text-sm font-medium mb-1">Exams</p>
                        <h3 class="text-5xl font-bold mb-2">{{ $stats['exams_count'] }}</h3>
                        <div class="flex items-center text-sm text-orange-100">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Total assessments</span>
                        </div>
                    </div>
                </div>
                        </svg>
                        <span>Total assessments</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🚀 Quick Actions -->
        <div class="card-modern p-8 shadow-xl border border-gray-100">
            <div class="section-header mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <span class="text-xl">🚀</span>
                    </div>
                    <div>
                        <h2 class="section-title">Quick Actions</h2>
                        <p class="section-subtitle">Frequently used tools and features</p>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Manage Subjects -->
                <a href="{{ route('teacher.subjects.index') }}" class="quick-action">
                    <div class="quick-action-icon bg-gradient-to-br from-blue-500 to-blue-600">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Manage Subjects</h3>
                    <p class="text-sm text-gray-500">Create and edit subjects</p>
                </a>

                <!-- Manage Topics -->
                <a href="{{ route('teacher.topics') }}" class="quick-action">
                    <div class="quick-action-icon bg-gradient-to-br from-purple-500 to-purple-600">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Manage Topics</h3>
                    <p class="text-sm text-gray-500">Organize learning topics</p>
                </a>

                <!-- Question Bank -->
                <a href="{{ route('teacher.questions.index') }}" class="quick-action">
                    <div class="quick-action-icon bg-gradient-to-br from-green-500 to-emerald-600">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Ngân hàng Câu hỏi</h3>
                    <p class="text-sm text-gray-500">Manage question pool</p>
                </a>

                <!-- Manage Exams -->
                <a href="{{ route('teacher.exams.index') }}" class="quick-action">
                    <div class="quick-action-icon bg-gradient-to-br from-orange-500 to-orange-600">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Quản lý Đề thi</h3>
                    <p class="text-sm text-gray-500">Create and manage exams</p>
                </a>

                <!-- Video Calls -->
                <a href="{{ route('teacher.video-calls.index') }}" class="quick-action">
                    <div class="quick-action-icon bg-gradient-to-br from-cyan-500 to-cyan-600">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Buổi học Online</h3>
                    <p class="text-sm text-gray-500">Schedule video calls</p>
                </a>

                <!-- Grading -->
                <a href="{{ route('teacher.grading.index') }}" class="quick-action">
                    <div class="quick-action-icon bg-gradient-to-br from-pink-500 to-pink-600">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Chấm điểm</h3>
                    <p class="text-sm text-gray-500">Grade student submissions</p>
                </a>

                <!-- Students -->
                <a href="{{ route('teacher.students.index') }}" class="quick-action">
                    <div class="quick-action-icon bg-gradient-to-br from-indigo-500 to-indigo-600">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Học sinh</h3>
                    <p class="text-sm text-gray-500">Manage students</p>
                </a>

                <!-- Documents -->
                <a href="{{ route('teacher.documents.index') }}" class="quick-action">
                    <div class="quick-action-icon bg-gradient-to-br from-yellow-500 to-yellow-600">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Tài liệu</h3>
                    <p class="text-sm text-gray-500">Upload documents</p>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
