@extends('layouts.app')

@section('title', 'Teacher Dashboard - MegaLearning')

@push('styles')
<style>
    /* ClassPoint Style - Modern LMS UI */
    body {
        background: linear-gradient(135deg, #F8FAFC 0%, #EEF2FF 100%);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }
    
    /* Pastel Gradient Backgrounds */
    .gradient-indigo {
        background: linear-gradient(135deg, #E0E7FF 0%, #C7D2FE 100%);
    }
    
    .gradient-purple {
        background: linear-gradient(135deg, #EDE9FE 0%, #DDD6FE 100%);
    }
    
    .gradient-blue {
        background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%);
    }
    
    .gradient-pink {
        background: linear-gradient(135deg, #FCE7F3 0%, #FBCFE8 100%);
    }
    
    /* Soft Card Shadow */
    .card-soft {
        box-shadow: 0 4px 24px rgba(99, 102, 241, 0.08);
        transition: all 0.3s ease;
    }
    
    .card-soft:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 32px rgba(99, 102, 241, 0.15);
    }
    
    /* Icon Styles */
    .icon-circle {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    /* Smooth Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
    
    /* Stat Card Number Animation */
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Quick Action Cards */
    .quick-action-card {
        background: white;
        border-radius: 24px;
        padding: 1.5rem;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    
    .quick-action-card:hover {
        border-color: #A5B4FC;
        background: linear-gradient(135deg, #FFFFFF 0%, #F5F3FF 100%);
        transform: translateY(-2px);
    }
    
    /* Search Bar Styling */
    .search-bar {
        background: white;
        border-radius: 24px;
        border: 2px solid #E0E7FF;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }
    
    .search-bar:focus-within {
        border-color: #A5B4FC;
        box-shadow: 0 0 0 4px rgba(165, 180, 252, 0.15);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen">
    <div class="max-w-7xl mx-auto px-6 py-8 space-y-8">
        
        <!-- Welcome Header -->
        <div class="mb-8 animate-fade-in-up">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-4xl font-bold text-gray-800 mb-2">Teacher Dashboard</h1>
                    <p class="text-lg text-gray-600">Welcome back, <span class="font-semibold text-indigo-600">{{ auth()->user()->name }}</span>! Here's what's happening with your classes today.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xl font-bold shadow-lg">
                        👨‍🏫
                    </div>
                </div>
            </div>
            
            <!-- Full-width Search Bar -->
            <div class="search-bar flex items-center gap-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" placeholder="Search for students, courses, or topics..." 
                       class="flex-1 outline-none text-gray-700 placeholder-gray-400 bg-transparent">
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Subjects Card -->
            <div class="card-soft bg-white rounded-3xl p-6 animate-fade-in-up" style="animation-delay: 0.1s">
                <div class="flex items-start justify-between mb-4">
                    <div class="icon-circle gradient-indigo">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-2">Total Subjects</h3>
                <div class="stat-number">{{ $stats['subjects_count'] }}</div>
                <p class="text-sm text-green-600 mt-2 flex items-center gap-1">
                    <span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span>
                    Active courses
                </p>
            </div>

            <!-- Topics Card -->
            <div class="card-soft bg-white rounded-3xl p-6 animate-fade-in-up" style="animation-delay: 0.2s">
                <div class="flex items-start justify-between mb-4">
                    <div class="icon-circle gradient-purple">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-2">Active Topics</h3>
                <div class="stat-number">{{ $stats['topics_count'] }}</div>
                <p class="text-sm text-blue-600 mt-2 flex items-center gap-1">
                    <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                    Learning modules
                </p>
            </div>

            <!-- Questions Card -->
            <div class="card-soft bg-white rounded-3xl p-6 animate-fade-in-up" style="animation-delay: 0.3s">
                <div class="flex items-start justify-between mb-4">
                    <div class="icon-circle gradient-blue">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-2">Question Bank</h3>
                <div class="stat-number">{{ $stats['questions_count'] }}</div>
                <p class="text-sm text-indigo-600 mt-2 flex items-center gap-1">
                    <span class="inline-block w-2 h-2 bg-purple-500 rounded-full"></span>
                    Available questions
                </p>
            </div>

            <!-- Exams Card -->
            <div class="card-soft bg-white rounded-3xl p-6 animate-fade-in-up" style="animation-delay: 0.4s">
                <div class="flex items-start justify-between mb-4">
                    <div class="icon-circle gradient-pink">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-2">Total Exams</h3>
                <div class="stat-number">{{ $stats['exams_count'] }}</div>
                <p class="text-sm text-purple-600 mt-2 flex items-center gap-1">
                    <span class="inline-block w-2 h-2 bg-orange-500 rounded-full"></span>
                    Assessments created
                </p>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Quick Actions</h2>
            <p class="text-gray-600 mb-6">Frequently used tools and features</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Manage Subjects -->
                <a href="{{ route('teacher.subjects.index') }}" class="quick-action-card card-soft group">
                    <div class="icon-circle gradient-indigo mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1">Manage Subjects</h3>
                    <p class="text-sm text-gray-600">Create and edit subjects</p>
                </a>

                <!-- Manage Topics -->
                <a href="{{ route('teacher.topics') }}" class="quick-action-card card-soft group">
                    <div class="icon-circle gradient-purple mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1">Manage Topics</h3>
                    <p class="text-sm text-gray-600">Organize learning topics</p>
                </a>

                <!-- Question Bank -->
                <a href="{{ route('teacher.questions.index') }}" class="quick-action-card card-soft group">
                    <div class="icon-circle gradient-blue mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1">Question Bank</h3>
                    <p class="text-sm text-gray-600">Manage question pool</p>
                </a>

                <!-- Manage Exams -->
                <a href="{{ route('teacher.exams.index') }}" class="quick-action-card card-soft group">
                    <div class="icon-circle gradient-pink mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1">Manage Exams</h3>
                    <p class="text-sm text-gray-600">Create and manage exams</p>
                </a>

                <!-- Video Calls -->
                <a href="{{ route('teacher.video-calls.index') }}" class="quick-action-card card-soft group">
                    <div class="icon-circle gradient-indigo mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1">Video Calls</h3>
                    <p class="text-sm text-gray-600">Schedule virtual classes</p>
                </a>

                <!-- Grading -->
                <a href="{{ route('teacher.grading.index') }}" class="quick-action-card card-soft group">
                    <div class="icon-circle gradient-purple mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1">Grading</h3>
                    <p class="text-sm text-gray-600">Grade student submissions</p>
                </a>

                <!-- Students -->
                <a href="{{ route('teacher.students') }}" class="quick-action-card card-soft group">
                    <div class="icon-circle gradient-blue mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1">Students</h3>
                    <p class="text-sm text-gray-600">Manage students</p>
                </a>

                <!-- Documents -->
                <a href="{{ route('teacher.documents.index') }}" class="quick-action-card card-soft group">
                    <div class="icon-circle gradient-pink mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-1">Documents</h3>
                    <p class="text-sm text-gray-600">Upload learning materials</p>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
