@extends('layouts.app')

@section('title', 'Dashboard - Giảng Viên')

@section('content')
<div class="space-y-8">
    
    <!-- Hero Banner -->
    <div class="relative overflow-hidden rounded-2xl shadow-xl h-64">
        <img src="{{ asset('images/hero-bg.jpg') }}" 
             alt="Teacher" 
             class="absolute inset-0 w-full h-full object-cover"
             onerror="this.style.display='none'; this.parentElement.classList.add('bg-gradient-to-r', 'from-indigo-600', 'to-purple-600')">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-900/90 via-indigo-800/85 to-purple-900/80"></div>
        <div class="relative h-full flex items-center justify-between p-8">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold text-white uppercase tracking-wider mb-2 drop-shadow-lg">Chào mừng trở lại!</p>
                <h1 class="text-4xl font-extrabold mb-3 text-white drop-shadow-lg">
                    {{ Auth::user()->name }}
                </h1>
                <p class="text-lg text-white mb-6 drop-shadow-lg">
                    Hôm nay bạn muốn làm gì với lớp học của mình?
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

    <!-- Quick Access Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <a href="{{ route('teacher.subjects.index') }}" class="group relative overflow-hidden rounded-2xl shadow-lg h-48 hover:shadow-xl transition">
            <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=600&q=80" 
                 alt="Subjects" 
                 class="absolute inset-0 w-full h-full object-cover transition group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/90 via-indigo-900/60 to-indigo-900/30"></div>
            <div class="relative h-full flex flex-col justify-end p-6 text-white">
                <div class="bg-indigo-500 w-14 h-14 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="font-extrabold text-2xl mb-1 text-white drop-shadow">Môn Học</h3>
                <p class="text-sm text-white font-medium">Quản lý môn học</p>
            </div>
        </a>

        <a href="{{ route('teacher.exams.index') }}" class="group relative overflow-hidden rounded-2xl shadow-lg h-48 hover:shadow-xl transition">
            <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=600&q=80" 
                 alt="Exams" 
                 class="absolute inset-0 w-full h-full object-cover transition group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/90 via-indigo-900/60 to-indigo-900/30"></div>
            <div class="relative h-full flex flex-col justify-end p-6 text-white">
                <div class="bg-green-500 w-14 h-14 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="font-extrabold text-2xl mb-1 text-white drop-shadow">Bài Thi</h3>
                <p class="text-sm text-white font-medium">Tạo và quản lý đề thi</p>
            </div>
        </a>

        <a href="{{ route('teacher.students') }}" class="group relative overflow-hidden rounded-2xl shadow-lg h-48 hover:shadow-xl transition">
            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=600&q=80" 
                 alt="Students" 
                 class="absolute inset-0 w-full h-full object-cover transition group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/90 via-indigo-900/60 to-indigo-900/30"></div>
            <div class="relative h-full flex flex-col justify-end p-6 text-white">
                <div class="bg-orange-500 w-14 h-14 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="font-extrabold text-2xl mb-1 text-white drop-shadow">Học Viên</h3>
                <p class="text-sm text-white font-medium">Quản lý học viên</p>
            </div>
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 p-6 hover:shadow-xl transition">
            <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=600&q=80" 
                 alt="Subjects" 
                 class="absolute inset-0 w-full h-full object-cover opacity-10">
            <div class="relative flex items-center justify-between">
                <div class="bg-blue-100 rounded-xl p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-800 text-xs font-bold uppercase tracking-wide">Môn Học</p>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['subjects_count'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 p-6 hover:shadow-xl transition">
            <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=600&q=80" 
                 alt="Topics" 
                 class="absolute inset-0 w-full h-full object-cover opacity-10">
            <div class="relative flex items-center justify-between">
                <div class="bg-green-100 rounded-xl p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-800 text-xs font-bold uppercase tracking-wide">Chủ Đề</p>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['topics_count'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 p-6 hover:shadow-xl transition">
            <img src="https://images.unsplash.com/photo-1606326608606-aa0b62935f2b?w=600&q=80" 
                 alt="Questions" 
                 class="absolute inset-0 w-full h-full object-cover opacity-10">
            <div class="relative flex items-center justify-between">
                <div class="bg-yellow-100 rounded-xl p-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-800 text-xs font-bold uppercase tracking-wide">Câu Hỏi</p>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['questions_count'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 p-6 hover:shadow-xl transition">
            <img src="https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=600&q=80" 
                 alt="Exams" 
                 class="absolute inset-0 w-full h-full object-cover opacity-10">
            <div class="relative flex items-center justify-between">
                <div class="bg-indigo-100 rounded-xl p-3">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-gray-800 text-xs font-bold uppercase tracking-wide">Bài Thi</p>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['exams_count'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="{{ route('teacher.subjects.index') }}" class="group relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 p-6 hover:shadow-xl hover:border-indigo-300 transition">
            <div class="flex flex-col items-center text-center">
                <div class="bg-indigo-100 rounded-xl p-4 mb-4 group-hover:bg-indigo-200 transition">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-1">Quản Lý Môn Học</h3>
                <p class="text-sm text-gray-600">Tạo và chỉnh sửa</p>
            </div>
        </a>

        <a href="{{ route('teacher.topics') }}" class="group relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 p-6 hover:shadow-xl hover:border-green-300 transition">
            <div class="flex flex-col items-center text-center">
                <div class="bg-green-100 rounded-xl p-4 mb-4 group-hover:bg-green-200 transition">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-1">Quản Lý Chủ Đề</h3>
                <p class="text-sm text-gray-600">Tổ chức nội dung</p>
            </div>
        </a>

        <a href="{{ route('teacher.questions.index') }}" class="group relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 p-6 hover:shadow-xl hover:border-blue-300 transition">
            <div class="flex flex-col items-center text-center">
                <div class="bg-blue-100 rounded-xl p-4 mb-4 group-hover:bg-blue-200 transition">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-1">Ngân Hàng Câu Hỏi</h3>
                <p class="text-sm text-gray-600">Quản lý câu hỏi</p>
            </div>
        </a>

        <a href="{{ route('teacher.exams.index') }}" class="group relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 p-6 hover:shadow-xl hover:border-purple-300 transition">
            <div class="flex flex-col items-center text-center">
                <div class="bg-purple-100 rounded-xl p-4 mb-4 group-hover:bg-purple-200 transition">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-1">Quản Lý Bài Thi</h3>
                <p class="text-sm text-gray-600">Tạo đề thi</p>
            </div>
        </a>

        <a href="{{ route('teacher.video-calls.index') }}" class="group relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 p-6 hover:shadow-xl hover:border-pink-300 transition">
            <div class="flex flex-col items-center text-center">
                <div class="bg-pink-100 rounded-xl p-4 mb-4 group-hover:bg-pink-200 transition">
                    <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-1">Video Call</h3>
                <p class="text-sm text-gray-600">Lớp học trực tuyến</p>
            </div>
        </a>

        <a href="{{ route('teacher.grading.index') }}" class="group relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 p-6 hover:shadow-xl hover:border-amber-300 transition">
            <div class="flex flex-col items-center text-center">
                <div class="bg-amber-100 rounded-xl p-4 mb-4 group-hover:bg-amber-200 transition">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-1">Chấm Điểm</h3>
                <p class="text-sm text-gray-600">Đánh giá bài thi</p>
            </div>
        </a>

        <a href="{{ route('teacher.students') }}" class="group relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 p-6 hover:shadow-xl hover:border-teal-300 transition">
            <div class="flex flex-col items-center text-center">
                <div class="bg-teal-100 rounded-xl p-4 mb-4 group-hover:bg-teal-200 transition">
                    <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-1">Học Viên</h3>
                <p class="text-sm text-gray-600">Quản lý lớp học</p>
            </div>
        </a>

        <a href="{{ route('teacher.documents.index') }}" class="group relative overflow-hidden rounded-2xl shadow-lg bg-white border border-gray-200 p-6 hover:shadow-xl hover:border-orange-300 transition">
            <div class="flex flex-col items-center text-center">
                <div class="bg-orange-100 rounded-xl p-4 mb-4 group-hover:bg-orange-200 transition">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-1">Tài Liệu</h3>
                <p class="text-sm text-gray-600">Tài liệu học tập</p>
            </div>
        </a>
    </div>

</div>
@endsection
