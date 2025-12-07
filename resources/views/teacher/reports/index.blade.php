@extends('layouts.app')

@section('title', 'Báo cáo & Thống kê')

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .stat-icon {
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
    }
    .subject-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .subject-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transform: translateY(-2px);
        border-color: #3b82f6;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">📊</span>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Báo cáo & Thống kê</h1>
                    <p class="text-gray-600">Reports & Analytics</p>
                </div>
            </div>
        </div>

        <!-- Overview Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Total Subjects -->
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Môn học của tôi</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $stats['total_subjects'] }}</h3>
                        <p class="text-xs text-blue-600 mt-2">{{ $stats['total_classes'] }} lớp học</p>
                    </div>
                    <div class="stat-icon bg-gradient-to-br from-blue-500 to-blue-600">
                        📚
                    </div>
                </div>
            </div>

            <!-- Total Students -->
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tổng học sinh</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ number_format($stats['total_students']) }}</h3>
                        <p class="text-xs text-green-600 mt-2">Đang quản lý</p>
                    </div>
                    <div class="stat-icon bg-gradient-to-br from-green-500 to-green-600">
                        👨‍🎓
                    </div>
                </div>
            </div>

            <!-- Total Exams -->
            <div class="stat-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Đề thi</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $stats['total_exams'] }}</h3>
                        <p class="text-xs text-purple-600 mt-2">{{ $stats['total_submissions'] }} bài nộp</p>
                    </div>
                    <div class="stat-icon bg-gradient-to-br from-purple-500 to-purple-600">
                        📝
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Grading Alert -->
        @if($stats['pending_grading'] > 0)
        <div class="bg-orange-50 border-l-4 border-orange-400 p-4 mb-8 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-3xl">⚠️</span>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-orange-700">
                        <strong class="font-medium">Có {{ $stats['pending_grading'] }} bài thi chưa chấm điểm!</strong>
                        <a href="{{ route('teacher.grading.index') }}" class="underline ml-2 hover:text-orange-800">
                            Đi đến trang chấm điểm →
                        </a>
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Subjects List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">📚 Môn học của tôi</h2>
            
            @if($subjects->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($subjects as $subject)
                        <div class="subject-card" onclick="window.location='{{ route('teacher.reports.subject-overview', $subject->id) }}'">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 text-lg mb-1">{{ $subject->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $subject->code }}</p>
                                </div>
                                <span class="text-3xl">📖</span>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Lớp học:</span>
                                    <span class="font-semibold text-blue-600">{{ $subject->class_rooms_count }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Đề thi:</span>
                                    <span class="font-semibold text-purple-600">{{ $subject->exams_count }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Tài liệu:</span>
                                    <span class="font-semibold text-green-600">{{ $subject->documents_count }}</span>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('teacher.reports.subject-overview', $subject->id) }}" 
                                   class="flex-1 bg-blue-50 text-blue-700 text-center text-sm py-2 rounded-lg hover:bg-blue-100 transition"
                                   onclick="event.stopPropagation()">
                                    📊 Tổng quan
                                </a>
                            </div>

                            <!-- Classes dropdown -->
                            @if($subject->classRooms->count() > 0)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-xs text-gray-500 mb-2">Lớp học:</p>
                                <div class="space-y-1">
                                    @foreach($subject->classRooms->take(3) as $class)
                                        <a href="{{ route('teacher.reports.class-performance', $class->id) }}" 
                                           class="block text-sm text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-2 py-1 rounded transition"
                                           onclick="event.stopPropagation()">
                                            → {{ $class->name }}
                                        </a>
                                    @endforeach
                                    @if($subject->classRooms->count() > 3)
                                        <p class="text-xs text-gray-400 px-2">
                                            +{{ $subject->classRooms->count() - 3 }} lớp khác...
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <span class="text-6xl mb-4 block">📚</span>
                    <p class="text-gray-500">Bạn chưa có môn học nào</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
