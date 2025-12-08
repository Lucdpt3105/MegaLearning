@extends('layouts.app')

@section('title', 'Dashboard - MegaLearning')

@section('content')
<div class="space-y-8">
    
    <div class="relative overflow-hidden rounded-2xl shadow-xl h-64">
        <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1200&q=80" 
             alt="Students Learning" 
             class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-900/90 via-indigo-800/85 to-purple-900/80"></div>
        <div class="relative h-full flex items-center justify-between p-8">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold text-indigo-200 uppercase tracking-wider mb-2">Chào mừng trở lại!</p>
                <h1 class="text-4xl font-extrabold mb-3 text-white drop-shadow-lg">
                    {{ Auth::user()->name }}
                </h1>
                <p class="text-lg text-indigo-100 mb-6">
                    Sẵn sàng tiếp tục hành trình học tập của bạn chưa?
                </p>
            </div>
            <div class="hidden lg:flex items-center justify-center">
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 border border-white/30">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <a href="{{ route('student.exams.index') }}" class="group relative overflow-hidden rounded-2xl shadow-lg h-48 hover:shadow-xl transition">
            <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=600&q=80" 
                 alt="Exam" 
                 class="absolute inset-0 w-full h-full object-cover transition group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/90 via-indigo-900/60 to-indigo-900/30"></div>
            <div class="relative h-full flex flex-col justify-end p-6 text-white">
                <div class="bg-indigo-500 w-14 h-14 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="font-extrabold text-2xl mb-1 text-white drop-shadow">Bài Kiểm Tra</h3>
                <p class="text-sm text-white font-medium">Xem danh sách bài thi</p>
            </div>
        </a>

        <a href="{{ route('student.courses.index') }}" class="group relative overflow-hidden rounded-2xl shadow-lg h-48 hover:shadow-xl transition">
            <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=600&q=80" 
                 alt="Books" 
                 class="absolute inset-0 w-full h-full object-cover transition group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/90 via-indigo-900/60 to-indigo-900/30"></div>
            <div class="relative h-full flex flex-col justify-end p-6 text-white">
                <div class="bg-green-500 w-14 h-14 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="font-extrabold text-2xl mb-1 text-white drop-shadow">Khóa Học</h3>
                <p class="text-sm text-white font-medium">Quản lý khóa học</p>
            </div>
        </a>

        <a href="{{ route('student.grades.index') }}" class="group relative overflow-hidden rounded-2xl shadow-lg h-48 hover:shadow-xl transition">
            <img src="https://images.unsplash.com/photo-1606326608606-aa0b62935f2b?w=600&q=80" 
                 alt="Charts" 
                 class="absolute inset-0 w-full h-full object-cover transition group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/90 via-indigo-900/60 to-indigo-900/30"></div>
            <div class="relative h-full flex flex-col justify-end p-6 text-white">
                <div class="bg-orange-500 w-14 h-14 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="font-extrabold text-2xl mb-1 text-white drop-shadow">Điểm Số</h3>
                <p class="text-sm text-white font-medium">Xem kết quả học tập</p>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 p-6 hover:shadow-xl transition">
            <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=600&q=80" 
                 alt="Books" 
                 class="absolute inset-0 w-full h-full object-cover opacity-10">
            <div class="relative flex items-center justify-between">
                <div class="bg-blue-100 rounded-xl p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-800 text-xs font-bold uppercase tracking-wide">Tổng Khóa Học</p>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['total_courses'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 p-6 hover:shadow-xl transition">
            <img src="https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=600&q=80" 
                 alt="Success" 
                 class="absolute inset-0 w-full h-full object-cover opacity-10">
            <div class="relative flex items-center justify-between">
                <div class="bg-green-100 rounded-xl p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-800 text-xs font-bold uppercase tracking-wide">Bài Đã Làm</p>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['completed_exams'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 p-6 hover:shadow-xl transition">
            <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=600&q=80" 
                 alt="Office" 
                 class="absolute inset-0 w-full h-full object-cover opacity-10">
            <div class="relative flex items-center justify-between">
                <div class="bg-yellow-100 rounded-xl p-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-800 text-xs font-bold uppercase tracking-wide">Điểm TB</p>
                    <p class="text-4xl font-black text-gray-900">{{ number_format($stats['average_score'], 1) }}</p>
                </div>
            </div>
        </div>
        
        <div class="relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 p-6 hover:shadow-xl transition">
            <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80" 
                 alt="Study" 
                 class="absolute inset-0 w-full h-full object-cover opacity-10">
            <div class="relative flex items-center justify-between">
                <div class="bg-indigo-100 rounded-xl p-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-800 text-xs font-bold uppercase tracking-wide">Môn Học</p>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['total_subjects'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 shadow-lg rounded-xl overflow-hidden">
            <div class="header-indigo">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Sự Kiện Sắp Tới
                </h2>
            </div>
            <div class="bg-white p-6">
                @if($upcomingEvents->isEmpty())
                    <div class="empty-state">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="font-medium">Chưa có sự kiện nào</p>
                        <p class="text-sm">Hãy đăng ký thêm khóa học để tham gia kiểm tra và lịch học</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($upcomingEvents as $event)
                            @if($event['type'] == 'exam')
                                <div class="event-card border-l-4 border-indigo-500 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="px-2 py-1 bg-indigo-600 text-white text-xs font-semibold rounded">BÀI THI</span>
                                                <h3 class="font-extrabold text-gray-900 text-base">{{ $event['title'] }}</h3>
                                            </div>
                                            <p class="text-sm text-gray-800 font-medium">{{ $event['subject'] }} - {{ $event['class'] }}</p>
                                            <div class="flex items-center gap-4 mt-2 text-xs text-gray-700 font-medium">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $event['datetime']->format('d/m/Y H:i') }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $event['duration'] }} phút
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                    </svg>
                                                    {{ $event['points'] }} điểm
                                                </span>
                                            </div>
                                        </div>
                                        <a href="{{ $event['url'] }}" class="btn-indigo-primary text-sm">
                                            Xem
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="event-card border-l-4 border-green-500 bg-green-50 hover:bg-green-100 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="px-2 py-1 bg-green-600 text-white text-xs font-semibold rounded flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                    </svg>
                                                    ZOOM
                                                </span>
                                                <h3 class="font-extrabold text-gray-900 text-base">{{ $event['title'] }}</h3>
                                            </div>
                                            <p class="text-sm text-gray-800 font-medium">{{ $event['subject'] }} - {{ $event['class'] }}</p>
                                            <div class="flex items-center gap-4 mt-2 text-xs text-gray-700 font-medium">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $event['datetime']->format('d/m/Y H:i') }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $event['duration'] }} phút
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    {{ $event['host'] }}
                                                </span>
                                            </div>
                                        </div>
                                        <a href="{{ $event['url'] }}" class="btn-green-primary text-sm">
                                            Tham gia
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('student.exams.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                            Xem tất cả sự kiện →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div>
            <div class="header-green">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Hoạt Động Gần Đây
                </h2>
            </div>
            <div class="bg-white p-6">
                @if($recentSubmissions->isEmpty())
                    <div class="empty-state py-8">
                        <p class="text-sm">Chưa có hoạt động nào</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($recentSubmissions as $submission)
                            <div class="p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors border-l-4 {{ $submission->score >= $submission->exam->passing_score ? 'border-green-500' : 'border-red-500' }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-extrabold text-gray-900 text-sm">{{ $submission->exam->title }}</h4>
                                        <p class="text-xs text-gray-700 mt-1">{{ $submission->submitted_at ? $submission->submitted_at->diffForHumans() : 'N/A' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-extrabold {{ $submission->score >= $submission->exam->passing_score ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($submission->score, 1) }}
                                        </div>
                                        <div class="text-xs text-gray-700">/ {{ $submission->exam->total_points }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('student.grades.index') }}" class="text-green-600 hover:text-green-800 font-medium text-sm">
                            Xem tất cả →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="shadow-lg rounded-xl overflow-hidden">
        <div class="header-purple">
            <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Khóa Học Đang Tham Gia
            </h2>
        </div>
        <div class="bg-white p-6">
            @if($enrolledClasses->isEmpty())
                <div class="empty-state py-12">
                    <svg class="w-20 h-20 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có khóa học nào</h3>
                    <p class="text-gray-500 mb-4">Hãy đăng ký khóa học để bắt đầu học tập</p>
                    <a href="{{ route('student.courses.browse') }}" class="btn-purple-primary">
                        Khám Phá Khóa Học
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($enrolledClasses as $class)
                        <div class="border border-gray-200 rounded-lg p-5 hover:shadow-lg transition-shadow">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="font-black text-gray-900 mb-1 text-base">{{ $class->name }}</h3>
                                    <p class="text-sm text-gray-800 font-semibold">{{ $class->subject->name ?? 'N/A' }}</p>
                                </div>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">Đang học</span>
                            </div>
                            <div class="flex gap-2 mt-4">
                                <a href="{{ route('student.courses.show', $class->id) }}" class="flex-1 btn-purple-primary text-center">
                                    Xem
                                </a>
                                <a href="{{ route('student.courses.materials', $class->id) }}" class="flex-1 btn-purple-secondary text-center">
                                    Tài Liệu
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 text-center">
                    <a href="{{ route('student.courses.index') }}" class="text-purple-600 hover:text-purple-800 font-medium">
                        Xem tất cả khóa học →
                    </a>
                </div>
            @endif
        </div>
    </div>

    @if(!$performanceBySubject->isEmpty())
        <div class="shadow-lg rounded-xl overflow-hidden">
            <div class="header-amber">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Thành Tích Theo Môn Học
                </h2>
            </div>
            <div class="bg-white p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($performanceBySubject as $subject => $performance)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <h4 class="font-extrabold text-indigo-600 mb-3">{{ $subject }}</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-800 font-semibold">Số bài thi:</span>
                                    <span class="font-extrabold text-gray-900">{{ $performance['count'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-800 font-semibold">Điểm TB:</span>
                                    <span class="font-extrabold text-blue-600">{{ number_format($performance['average'], 1) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-800 font-semibold">Điểm cao nhất:</span>
                                    <span class="font-black text-green-600">{{ number_format($performance['highest'], 1) }}</span>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-4">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $performance['average'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

@endsection