@extends('layouts.app')

@section('title', 'Dashboard - MegaLearning')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Chào mừng trở lại, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-purple-100 text-lg">Sẵn sàng tiếp tục hành trình học tập của bạn chưa?</p>
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
        <a href="{{ route('student.exams.index') }}" class="group bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
            <div class="flex items-center space-x-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3 overflow-hidden">
                    <img src="https://media2.giphy.com/media/v1.Y2lkPTc5MGI3NjExM2VhMjhmM25odnpnbXNxMTgxd2xibXlzOW1taGtocno5cDEwNjFkZyZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/v8jUfaclrsG9x8At9Z/giphy.gif" alt="Exam" class="w-12 h-12 object-cover rounded">
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-lg">Bài Kiểm Tra</h3>
                    <p class="text-sm text-purple-100">Xem danh sách bài thi</p>
                </div>
                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        <a href="{{ route('student.courses.index') }}" class="group bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
            <div class="flex items-center space-x-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3 overflow-hidden">
                    <img src="https://media1.giphy.com/media/v1.Y2lkPTc5MGI3NjExdjk1am9yeDF3Zzk0M21sZGxjYW5qaHRuOXA0dmcyZ3Bod3EzcnZ4YSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/efXKxAYtPFPDaz31wA/giphy.gif" alt="Grades" class="w-12 h-12 object-cover rounded">
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-lg">Khóa Học</h3>
                    <p class="text-sm text-green-100">Quản lý khóa học</p>
                </div>
                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        <a href="{{ route('student.grades.index') }}" class="group bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
            <div class="flex items-center space-x-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3 overflow-hidden">
                    <img src="https://media1.giphy.com/media/v1.Y2lkPTc5MGI3NjExZzdjNWdkZHduZGtlbjczM3U4enNlazZxb204N2xoZGJ2cW9tY2hhZiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/fn8glaawd4iAc15ssA/giphy.gif" alt="Grades" class="w-12 h-12 object-cover rounded">
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-lg">Điểm Số</h3>
                    <p class="text-sm text-orange-100">Xem kết quả học tập</p>
                </div>
                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Tổng Khóa Học</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_courses'] }}</p>
                </div>
                <div class="text-4xl">📚</div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Bài Đã Làm</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['completed_exams'] }}</p>
                </div>
                <div class="text-4xl">✅</div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Điểm Trung Bình</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['average_score'], 1) }}</p>
                </div>
                <div class="text-4xl">⭐</div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Môn Học</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_subjects'] }}</p>
                </div>
                <div class="text-4xl">📖</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upcoming Events -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Sự Kiện Sắp Tới
                    </h2>
                </div>
                <div class="p-6">
                    @if($upcomingEvents->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="font-medium">Chưa có sự kiện nào</p>
                            <p class="text-sm">Hãy đăng ký thêm khóa học để tham gia kiểm tra và lịch học</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($upcomingEvents as $event)
                                @if($event['type'] == 'exam')
                                    <!-- Exam Event -->
                                    <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded-r-lg hover:bg-blue-100 transition-colors">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="px-2 py-1 bg-blue-600 text-white text-xs font-semibold rounded">BÀI THI</span>
                                                    <h3 class="font-semibold text-gray-900">{{ $event['title'] }}</h3>
                                                </div>
                                                <p class="text-sm text-gray-600">{{ $event['subject'] }} - {{ $event['class'] }}</p>
                                                <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                                    <span>📅 {{ $event['datetime']->format('d/m/Y H:i') }}</span>
                                                    <span>⏱️ {{ $event['duration'] }} phút</span>
                                                    <span>📊 {{ $event['points'] }} điểm</span>
                                                </div>
                                            </div>
                                            <a href="{{ $event['url'] }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                                Xem
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <!-- Video Call Event -->
                                    <div class="border-l-4 border-green-500 bg-green-50 p-4 rounded-r-lg hover:bg-green-100 transition-colors">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="px-2 py-1 bg-green-600 text-white text-xs font-semibold rounded">📹 ZOOM</span>
                                                    <h3 class="font-semibold text-gray-900">{{ $event['title'] }}</h3>
                                                </div>
                                                <p class="text-sm text-gray-600">{{ $event['subject'] }} - {{ $event['class'] }}</p>
                                                <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                                    <span>📅 {{ $event['datetime']->format('d/m/Y H:i') }}</span>
                                                    <span>⏱️ {{ $event['duration'] }} phút</span>
                                                    <span>👨‍🏫 {{ $event['host'] }}</span>
                                                </div>
                                            </div>
                                            <a href="{{ $event['url'] }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                                Tham gia
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('student.exams.index') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm mr-4">
                                Xem tất cả bài thi →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Submissions -->
        <div>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Hoạt Động Gần Đây
                    </h2>
                </div>
                <div class="p-6">
                    @if($recentSubmissions->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <p class="text-sm">Chưa có hoạt động nào</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($recentSubmissions as $submission)
                                <div class="border-l-4 {{ $submission->score >= $submission->exam->passing_score ? 'border-green-500' : 'border-red-500' }} bg-gray-50 p-3 rounded-r-lg">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900 text-sm">{{ $submission->exam->title }}</h4>
                                            <p class="text-xs text-gray-500 mt-1">{{ $submission->submitted_at->diffForHumans() }}</p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-bold {{ $submission->score >= $submission->exam->passing_score ? 'text-green-600' : 'text-red-600' }}">
                                                {{ number_format($submission->score, 1) }}
                                            </div>
                                            <div class="text-xs text-gray-500">/ {{ $submission->exam->total_points }}</div>
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
    </div>

    <!-- Enrolled Courses -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Khóa Học Đang Tham Gia
            </h2>
        </div>
        <div class="p-6">
            @if($enrolledClasses->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-20 h-20 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có khóa học nào</h3>
                    <p class="text-gray-500 mb-4">Hãy đăng ký khóa học để bắt đầu học tập</p>
                    <a href="{{ route('student.courses.browse') }}" class="inline-block px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">
                        Khám Phá Khóa Học
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($enrolledClasses as $class)
                        <div class="border border-gray-200 rounded-lg p-5 hover:shadow-lg transition-shadow">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-900 mb-1">{{ $class->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $class->subject->name ?? 'N/A' }}</p>
                                </div>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">Đang học</span>
                            </div>
                            <div class="flex gap-2 mt-4">
                                <a href="{{ route('student.courses.show', $class->id) }}" class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium text-center">
                                    Xem
                                </a>
                                <a href="{{ route('student.courses.materials', $class->id) }}" class="flex-1 px-4 py-2 border border-purple-600 text-purple-600 rounded-lg hover:bg-purple-50 transition-colors text-sm font-medium text-center">
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

    <!-- Performance by Subject -->
    @if(!$performanceBySubject->isEmpty())
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Thành Tích Theo Môn Học
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($performanceBySubject as $subject => $performance)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">{{ $subject }}</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Số bài thi:</span>
                                    <span class="font-medium">{{ $performance['count'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Điểm TB:</span>
                                    <span class="font-medium text-blue-600">{{ number_format($performance['average'], 1) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Điểm cao nhất:</span>
                                    <span class="font-medium text-green-600">{{ number_format($performance['highest'], 1) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
