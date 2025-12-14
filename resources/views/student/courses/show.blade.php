@extends('layouts.app')

@section('title', $classRoom->name . ' - Course Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('student.courses.index') }}" class="hover:text-indigo-600">Lớp Học Của Tôi</a></li>
            <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
            <li class="text-gray-900 font-semibold">{{ $classRoom->name }}</li>
        </ol>
    </nav>

    <!-- Course Header -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg p-8 text-white mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold mb-2">{{ $classRoom->name }}</h1>
                <p class="text-indigo-100 text-lg mb-2">{{ $classRoom->subject ? $classRoom->subject->name : 'N/A' }}</p>
                <div class="flex items-center space-x-4 text-sm">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $classRoom->teacher ? $classRoom->teacher->name : 'N/A' }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                        </svg>
                        {{ $classRoom->code }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ $classRoom->active_students_count }} học viên
                    </span>
                </div>
            </div>
            <div class="text-right">
                <div class="bg-white rounded-lg p-4 shadow-md">
                    <p class="text-sm text-gray-600 font-semibold mb-2">Tiến Độ Học Tập</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $progressPercentage }}%</p>
                </div>
            </div>
        </div>

        <!-- Enrollment Info -->
        <div class="mt-6 pt-6 border-t border-indigo-400">
            <div class="flex items-center justify-between text-sm text-white">
                <span>Ngày đăng ký: {{ $enrollment->enrolled_at->format('d/m/Y H:i') }}</span>
                <span class="px-3 py-1 rounded-full font-medium {{ $enrollment->status === 'active' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                    Trạng thái: {{ $enrollment->status === 'active' ? 'Đang Học' : 'Không Học' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('student.courses.show', $classRoom->id) }}" class="border-b-2 border-indigo-500 py-4 px-1 text-sm font-medium text-indigo-600">
                Tổng Quan
            </a>
            <a href="{{ route('student.courses.materials', $classRoom->id) }}" class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Tài Liệu
            </a>
            <a href="{{ route('student.courses.schedule', $classRoom->id) }}" class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Lịch Học
            </a>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Course Description -->
            @if($classRoom->description)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Mô Tả Lớp Học
                </h2>
                <p class="text-gray-700 leading-relaxed">{{ $classRoom->description }}</p>
            </div>
            @endif

            <!-- Course Progress -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Tiến Độ Học Tập
                </h2>

                <div class="mb-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>{{ $completedTopics }}/{{ $totalTopics }} chủ đề hoàn thành</span>
                        <span>{{ $progressPercentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full transition-all duration-500" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 mt-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-2xl font-bold text-blue-600">{{ $totalTopics }}</p>
                        <p class="text-sm text-gray-600">Chủ Đề</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-2xl font-bold text-green-600">{{ $classRoom->active_students_count }}</p>
                        <p class="text-sm text-gray-600">Sĩ Số</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <p class="text-2xl font-bold text-purple-600">{{ $upcomingCalls->count() }}</p>
                        <p class="text-sm text-gray-600">Buổi Học</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Hành Động Nhanh</h2>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('student.courses.materials', $classRoom->id) }}" class="flex items-center p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors duration-200">
                        <svg class="w-8 h-8 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-900">Tài Liệu</p>
                            <p class="text-sm text-gray-600">Xem tài liệu</p>
                        </div>
                    </a>
                    <a href="{{ route('forum.index') }}" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors duration-200">
                        <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-900">Diễn Đàn</p>
                            <p class="text-sm text-gray-600">Thảo luận</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Teacher Info -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Giảng Viên</h3>
                <div class="flex items-center space-x-3">
                    @if($classRoom->teacher->avatar)
                        <img src="{{ asset('storage/' . $classRoom->teacher->avatar) }}" alt="{{ $classRoom->teacher->name }}" class="w-12 h-12 rounded-full">
                    @else
                        <div class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                            {{ substr($classRoom->teacher->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <p class="font-semibold text-gray-900">{{ $classRoom->teacher ? $classRoom->teacher->name : 'N/A' }}</p>
                        <p class="text-sm text-gray-600">{{ $classRoom->teacher->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Upcoming Video Calls -->
            @if($upcomingCalls->isNotEmpty())
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Lịch Học Sắp Tới
                </h3>
                <div class="space-y-3">
                    @foreach($upcomingCalls->take(3) as $call)
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="font-semibold text-gray-900 text-sm">{{ $call->title }}</p>
                        <p class="text-xs text-gray-600 mt-1">
                            {{ $call->scheduled_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('student.courses.schedule', $classRoom->id) }}" class="block mt-4 text-center text-sm text-indigo-600 hover:text-indigo-700 font-semibold">
                    Xem Tất Cả →
                </a>
            </div>
            @endif

            <!-- Students List -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Sĩ số ({{ $classRoom->active_students_count }}/{{ $classRoom->max_students }})
                </h3>
                <div class="space-y-2 max-h-60 overflow-y-auto">
                    @forelse($classRoom->students as $classStudent)
                    <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg">
                        @if($classStudent->avatar)
                            <img src="{{ asset('storage/' . $classStudent->avatar) }}" alt="{{ $classStudent->name }}" class="w-8 h-8 rounded-full">
                        @else
                            <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold">
                                {{ substr($classStudent->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $classStudent->name }}</p>
                            <p class="text-xs text-gray-500">{{ $classStudent->email }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">Chưa có học viên nào</p>
                    @endforelse
                </div>
            </div>

            <!-- Course Info -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Thông Tin Lớp Học</h3>
                <div class="space-y-3 text-sm">
                    @if($classRoom->start_date && $classRoom->end_date)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Thời gian:</span>
                        <span class="font-semibold text-gray-900">
                            {{ $classRoom->start_date->format('d/m/Y') }} - {{ $classRoom->end_date->format('d/m/Y') }}
                        </span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Sĩ số:</span>
                        <span class="font-semibold text-gray-900">{{ $classRoom->active_students_count }}/{{ $classRoom->max_students }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Trạng thái:</span>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            @if($classRoom->status === 'active') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $classRoom->status === 'active' ? 'Đang Hoạt Động' : ucfirst($classRoom->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
