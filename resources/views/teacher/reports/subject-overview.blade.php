@extends('layouts.app')

@section('title', 'Tổng quan Môn học')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('teacher.reports.index') }}" class="text-blue-600 hover:text-blue-700 text-sm mb-2 inline-block">
                ← Quay lại Báo cáo
            </a>
            <h1 class="text-3xl font-bold text-gray-800">{{ $subject->name }}</h1>
            <p class="text-gray-600">{{ $subject->code }} • Tổng quan môn học</p>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-600">Học sinh</p>
                    <span class="text-2xl">👨‍🎓</span>
                </div>
                <p class="text-3xl font-bold text-gray-800">{{ number_format($subjectStats['total_students']) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $subjectStats['total_classes'] }} lớp</p>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-600">Đề thi</p>
                    <span class="text-2xl">📝</span>
                </div>
                <p class="text-3xl font-bold text-gray-800">{{ $subjectStats['total_exams'] }}</p>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-600">Tài liệu</p>
                    <span class="text-2xl">📄</span>
                </div>
                <p class="text-3xl font-bold text-gray-800">{{ $subjectStats['total_documents'] }}</p>
                <p class="text-xs text-green-600 mt-1">{{ $subjectStats['approved_documents'] }} đã duyệt</p>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-gray-600">Video Call</p>
                    <span class="text-2xl">📹</span>
                </div>
                <p class="text-3xl font-bold text-gray-800">{{ $subjectStats['total_video_calls'] }}</p>
                <p class="text-xs text-blue-600 mt-1">{{ $subjectStats['total_call_duration'] }} phút</p>
            </div>
        </div>

        <!-- Classes List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">📚 Các lớp học</h2>
            
            @if($subject->classRooms->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($subject->classRooms as $class)
                        <a href="{{ route('teacher.reports.class-performance', $class->id) }}" 
                           class="block p-4 border border-gray-200 rounded-lg hover:border-blue-500 hover:shadow-md transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-bold text-gray-800">{{ $class->name }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $class->enrollments->count() }} học sinh
                                    </p>
                                </div>
                                <span class="text-blue-600">→</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Chưa có lớp học nào</p>
            @endif
        </div>
    </div>
</div>
@endsection
