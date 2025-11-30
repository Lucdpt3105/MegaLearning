@extends('layouts.app')

@section('title', 'Điểm Số - MegaLearning')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Bảng Điểm Của Tôi</h1>
        <p class="text-gray-600">Xem kết quả các bài kiểm tra đã hoàn thành</p>
    </div>

    <!-- Overall Statistics -->
    @if($submissions->isNotEmpty())
        <div class="grid grid-cols-2 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ $stats['total_exams'] }}</div>
                <div class="text-sm text-gray-600">Tổng Số Bài Thi</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">{{ number_format($stats['average_score'], 1) }}</div>
                <div class="text-sm text-gray-600">Điểm Trung Bình</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">{{ number_format($stats['highest_score'], 1) }}</div>
                <div class="text-sm text-gray-600">Điểm Cao Nhất</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-3xl font-bold text-orange-600 mb-2">{{ number_format($stats['lowest_score'], 1) }}</div>
                <div class="text-sm text-gray-600">Điểm Thấp Nhất</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="text-3xl font-bold text-teal-600 mb-2">{{ $stats['passed_count'] }}/{{ $stats['total_exams'] }}</div>
                <div class="text-sm text-gray-600">Bài Đạt</div>
            </div>
        </div>
    @endif

    <!-- Grades by Class -->
    @if($gradesByClass->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có điểm số</h3>
            <p class="mt-1 text-sm text-gray-500">Bạn chưa có bài kiểm tra nào được chấm điểm.</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach($gradesByClass as $classData)
                <div class="bg-white rounded-lg shadow">
                    <!-- Class Header -->
                    <div class="border-b border-gray-200 p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">{{ $classData['class']->name }}</h2>
                                <p class="text-gray-600 mt-1">{{ $classData['class']->subject->name ?? 'N/A' }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-blue-600">{{ number_format($classData['average_score'], 1) }}</div>
                                <div class="text-sm text-gray-600">Điểm TB</div>
                            </div>
                        </div>
                        <div class="mt-4 flex gap-4 text-sm">
                            <span class="text-gray-600">
                                📊 {{ $classData['total_exams'] }} bài thi
                            </span>
                            <span class="text-green-600">
                                ✓ {{ $classData['passed_exams'] }} bài đạt
                            </span>
                        </div>
                    </div>

                    <!-- Submissions Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bài Kiểm Tra</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày Nộp</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Lần</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Điểm</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng Thái</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($classData['submissions'] as $submission)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $submission->exam->title }}</div>
                                            <div class="text-sm text-gray-500">{{ $submission->exam->type }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $submission->submitted_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm text-gray-600">
                                            Lần {{ $submission->attempt_number }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="font-bold text-lg {{ $submission->score >= $submission->exam->passing_score ? 'text-green-600' : 'text-red-600' }}">
                                                {{ number_format($submission->score, 1) }}
                                            </div>
                                            <div class="text-xs text-gray-500">/ {{ $submission->exam->total_points }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($submission->score >= $submission->exam->passing_score)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Đạt
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Chưa Đạt
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('student.grades.show', $submission->id) }}" 
                                               class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                                Xem Chi Tiết →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
