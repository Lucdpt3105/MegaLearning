@extends('layouts.app')

@section('title', 'My Courses - MegaLearning')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Lớp Học Của Tôi</h1>
        <p class="text-gray-600">Quản lý và theo dõi các lớp học bạn đang tham gia</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card 1: Tổng Lớp Học -->
        <div class="relative overflow-hidden rounded-2xl shadow-lg h-32 bg-white border border-gray-200">
            <img src="https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=600&q=80" 
                 alt="Books" 
                 class="absolute inset-0 w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-[#0056D2]/10"></div>
            <div class="relative h-full flex items-center justify-between p-6">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Tổng Lớp Học</p>
                    <p class="text-4xl font-bold text-[#0056D2] mt-1">{{ $stats['total_courses'] }}</p>
                </div>
                <div class="bg-[#0056D2]/10 rounded-full p-4">
                    <svg class="w-8 h-8 text-[#0056D2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 2: Đang Hoạt Động -->
        <div class="relative overflow-hidden rounded-2xl shadow-lg h-32 bg-white border border-gray-200">
            <img src="https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=600&q=80" 
                 alt="Active Learning" 
                 class="absolute inset-0 w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-green-600/10"></div>
            <div class="relative h-full flex items-center justify-between p-6">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Đang Hoạt Động</p>
                    <p class="text-4xl font-bold text-green-600 mt-1">{{ $stats['active_courses'] }}</p>
                </div>
                <div class="bg-green-600/10 rounded-full p-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 3: Môn Học -->
        <div class="relative overflow-hidden rounded-2xl shadow-lg h-32 bg-white border border-gray-200">
            <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80" 
                 alt="Subjects" 
                 class="absolute inset-0 w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-purple-600/10"></div>
            <div class="relative h-full flex items-center justify-between p-6">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Môn Học</p>
                    <p class="text-4xl font-bold text-purple-600 mt-1">{{ $stats['total_subjects'] }}</p>
                </div>
                <div class="bg-purple-600/10 rounded-full p-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Browse More Courses Button -->
    <div class="mb-6">
        <a href="{{ route('student.courses.browse') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Khám Phá Lớp Học Mới
        </a>
    </div>

    <!-- Courses List -->
    @if($enrolledClasses->isEmpty())
        <div class="bg-white rounded-xl shadow-md p-12 text-center">
            <div class="max-w-md mx-auto">
                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có lớp học nào</h3>
                <p class="text-gray-600 mb-6">Bạn chưa đăng ký lớp học nào. Hãy khám phá và đăng ký ngay!</p>
                <a href="{{ route('student.courses.browse') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition-colors duration-200">
                    Khám Phá Lớp Học
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                // Array of beautiful course images from Unsplash
                $courseImages = [
                    'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=600&q=80', // Coding
                    'https://images.unsplash.com/photo-1509228468518-180dd4864904?w=600&q=80', // Math/Physics
                    'https://images.unsplash.com/photo-1635070041078-e363dbe005cb?w=600&q=80', // Science
                    'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=600&q=80', // Library
                    'https://images.unsplash.com/photo-1516397281156-ca07cf9746fc?w=600&q=80', // Learning
                    'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80', // Study
                ];
            @endphp
            @foreach($enrolledClasses as $index => $class)
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                    <!-- Course Image Header -->
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ $courseImages[$index % count($courseImages)] }}" 
                             alt="{{ $class->name }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/40 to-transparent"></div>
                        
                        <!-- Status Badge on Image -->
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold backdrop-blur-md
                                @if($class->status === 'active') bg-green-500/90 text-white
                                @elseif($class->status === 'archived') bg-gray-500/90 text-white
                                @else bg-red-500/90 text-white
                                @endif">
                                @if($class->status === 'active')
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"/>
                                    </svg>
                                    Đang Học
                                @elseif($class->status === 'archived')
                                    Đã Lưu Trữ
                                @else
                                    Đã Đóng
                                @endif
                            </span>
                        </div>
                        
                        <!-- Course Title on Image -->
                        <div class="absolute bottom-4 left-4 right-4">
                            <h3 class="text-xl font-bold text-white mb-1 drop-shadow-lg">{{ $class->name }}</h3>
                            <p class="text-sm text-gray-200 drop-shadow-md">{{ $class->subject->name }}</p>
                        </div>
                    </div>

                    <!-- Course Body -->
                    <div class="p-5">
                        <!-- Course Code -->
                        <div class="flex items-center text-sm text-gray-600 mb-3 bg-gray-50 rounded-lg px-3 py-2">
                            <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                            <span class="font-mono font-semibold text-gray-900">{{ $class->code }}</span>
                        </div>

                        <!-- Teacher -->
                        <div class="flex items-center text-sm text-gray-700 mb-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-xs mr-2">
                                {{ substr($class->teacher->name, 0, 2) }}
                            </div>
                            <span class="font-medium">{{ $class->teacher->name }}</span>
                        </div>

                        <!-- Students Count -->
                        <div class="flex items-center text-sm text-gray-700 mb-3">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>{{ $class->students_count }}/{{ $class->max_students }} học viên</span>
                        </div>

                        <!-- Course Duration -->
                        @if($class->start_date && $class->end_date)
                        <div class="flex items-center text-sm text-gray-700 mb-4">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $class->start_date->format('d/m/Y') }} - {{ $class->end_date->format('d/m/Y') }}</span>
                        </div>
                        @endif

                        <!-- Action Button -->
                        <a href="{{ route('student.courses.show', $class->id) }}" 
                           class="block w-full text-center px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                            <span class="flex items-center justify-center">
                                Xem Chi Tiết
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
