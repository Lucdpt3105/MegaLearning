@extends('layouts.app')

@section('title', 'Quản lý Học sinh')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Quản lý Học sinh</h1>
            <p class="text-gray-600 mt-2 text-base">Quản lý danh sách học sinh trong các lớp học</p>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Classes Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Tổng số lớp</p>
                        <p class="text-4xl font-bold text-gray-900">{{ $classRooms->total() }}</p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-2xl">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Students Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Tổng học sinh</p>
                        <p class="text-4xl font-bold text-gray-900">{{ $classRooms->sum('students_count') }}</p>
                    </div>
                    <div class="bg-green-100 p-4 rounded-2xl">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Average Students Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Trung bình/Lớp</p>
                        <p class="text-4xl font-bold text-gray-900">
                            {{ $classRooms->total() > 0 ? round($classRooms->sum('students_count') / $classRooms->total(), 1) : 0 }}
                        </p>
                    </div>
                    <div class="bg-purple-100 p-4 rounded-2xl">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Class List Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-900">Danh sách lớp học</h2>
            </div>

            @if($classRooms->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lớp học</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Môn học</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Số học sinh</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Thời gian</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classRooms as $index => $class)
                        <tr class="border-b border-gray-50 hover:bg-blue-50/30 transition-colors {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50/50' }}">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr($class->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $class->name }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $class->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $class->subject->name }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $class->subject->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="inline-flex items-baseline gap-1">
                                    <span class="text-xl font-bold text-gray-900">{{ $class->students_count }}</span>
                                    @if($class->max_students)
                                    <span class="text-sm text-gray-500">/ {{ $class->max_students }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                @if($class->status === 'active')
                                <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full bg-green-100 text-green-700 border border-green-200">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-2"></span>
                                    Hoạt động
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-2"></span>
                                    Đã đóng
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $class->start_date ? $class->start_date->format('d/m/Y') : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <a href="{{ route('teacher.students.show', $class) }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition-all shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    Quản lý học sinh
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($classRooms->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $classRooms->links() }}
            </div>
            @endif

            @else
            <div class="p-16 text-center">
                <div class="bg-gray-100 w-20 h-20 rounded-2xl mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Chưa có lớp học nào</h3>
                <p class="text-gray-600 mb-6">Bắt đầu bằng cách tạo môn học và lớp học mới</p>
                <a href="{{ route('teacher.subjects.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Quản lý môn học
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
