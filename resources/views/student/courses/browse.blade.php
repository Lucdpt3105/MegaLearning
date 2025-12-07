@extends('layouts.app')

@section('title', 'Khám Phá Khóa Học - MegaLearning')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Khám Phá Khóa Học</h1>
        <p class="text-gray-600">Tìm và đăng ký các khóa học phù hợp với bạn</p>
    </div>

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('student.courses.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-semibold">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Quay lại khóa học của tôi
        </a>
    </div>

    <!-- Available Courses -->
    @if($availableClasses->isEmpty())
        <div class="bg-white rounded-xl shadow-md p-12 text-center">
            <div class="max-w-md mx-auto">
                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Không có khóa học mới</h3>
                <p class="text-gray-600 mb-6">Hiện tại không có khóa học nào khả dụng để đăng ký. Vui lòng quay lại sau!</p>
                <a href="{{ route('student.courses.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition-colors duration-200">
                    Quay Lại
                </a>
            </div>
        </div>
    @else
        <!-- Courses by Subject -->
        @foreach($coursesBySubject as $subjectName => $courses)
            <div class="mb-10">
                <!-- Subject Header -->
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-3 mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $subjectName }}</h2>
                        <p class="text-sm text-gray-600">{{ $courses->count() }} khóa học khả dụng</p>
                    </div>
                </div>

                <!-- Courses Flexbox Grid -->
                <div class="flex flex-wrap gap-6">
                    @foreach($courses as $class)
                        <div class="w-[calc(33.333%-16px)] bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                            <!-- Course Header -->
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white">
                                <h3 class="text-xl font-bold mb-2">{{ $class->name }}</h3>
                                <p class="text-indigo-100 text-sm">{{ $class->subject->name }}</p>
                            </div>

                            <!-- Course Body -->
                            <div class="p-6">
                                <!-- Course Code -->
                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                    <span class="font-mono font-semibold">{{ $class->code }}</span>
                                </div>

                                <!-- Teacher -->
                                <div class="flex items-center text-sm text-gray-700 mb-3">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span>{{ $class->teacher->name }}</span>
                                </div>

                                <!-- Students Count -->
                                <div class="flex items-center text-sm mb-4">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span class="
                                        @if($class->students_count >= $class->max_students * 0.9) text-red-600 font-semibold
                                        @elseif($class->students_count >= $class->max_students * 0.7) text-yellow-600 font-semibold
                                        @else text-gray-700
                                        @endif
                                    ">
                                        {{ $class->students_count }}/{{ $class->max_students }} học viên
                                    </span>
                                    @if($class->students_count >= $class->max_students * 0.9)
                                        <span class="ml-2 px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded">Sắp đầy</span>
                                    @endif
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

                                <!-- Description -->
                                @if($class->description)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $class->description }}</p>
                                </div>
                                @endif

                                <!-- Availability Status -->
                                <div class="mb-4">
                                    @if($class->students_count < $class->max_students)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            Còn Chỗ Trống
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            Đã Đầy
                                        </span>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="space-y-2">
                                    @if($class->students_count < $class->max_students)
                                        <form action="{{ route('student.courses.enroll', $class->id) }}" method="POST">
                                            @csrf
                                            <button 
                                                type="submit"
                                                class="block w-full text-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors duration-200"
                                            >
                                                Đăng Ký Ngay
                                            </button>
                                        </form>
                                    @else
                                        <button 
                                            disabled 
                                            class="block w-full text-center px-4 py-2 bg-gray-300 text-gray-500 font-semibold rounded-lg cursor-not-allowed"
                                        >
                                            Đã Đầy
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
