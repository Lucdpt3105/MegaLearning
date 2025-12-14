@extends('admin.layout')

@section('title', 'Kết quả thi')
@section('page-title', 'Kết quả thi: ' . $exam->title)
@section('page-description', 'Xem kết quả bài thi của học sinh')

@section('content')

<!-- Header Actions -->
<div class="mb-6 flex items-center justify-between">
    <div>
        <h3 class="text-lg font-semibold text-gray-900">{{ $exam->title }}</h3>
        <p class="text-sm text-gray-600">
            {{ $exam->subject ? $exam->subject->name : 'N/A' }} - 
            {{ $exam->classRoom ? $exam->classRoom->name : 'Chưa có lớp' }}
        </p>
    </div>
    
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.exams.index') }}" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center gap-2">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
            Quay lại
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Tổng bài nộp</div>
        <div class="text-3xl font-bold text-gray-900">{{ $submissions->total() }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Đã chấm</div>
        <div class="text-3xl font-bold text-green-600">
            {{ $submissions->whereIn('grading_status', ['graded', 'auto_graded'])->count() }}
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Chờ chấm</div>
        <div class="text-3xl font-bold text-yellow-600">
            {{ $submissions->where('grading_status', 'pending')->where('status', 'submitted')->count() }}
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Điểm TB</div>
        <div class="text-3xl font-bold text-blue-600">
            @php
                $gradedSubmissions = $submissions->whereIn('grading_status', ['graded', 'auto_graded'])->whereNotNull('score');
                $avgScore = $gradedSubmissions->avg('score');
            @endphp
            {{ $avgScore ? number_format($avgScore, 2) : 'N/A' }}
        </div>
    </div>
</div>

<!-- Results Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">STT</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Học sinh</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Lần thi</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Thời gian nộp</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Thời gian làm</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Điểm</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Người chấm</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse($submissions as $index => $submission)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="px-4 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                    
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-semibold">
                                {{ substr($submission->student ? $submission->student->name : 'N/A', 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $submission->student ? $submission->student->name : 'N/A' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $submission->student ? $submission->student->email : '' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    
                    <td class="px-4 py-4 text-center text-sm text-gray-900">
                        {{ $submission->attempt_number }}
                    </td>
                    
                    <td class="px-4 py-4 text-center text-sm text-gray-600">
                        @if($submission->submitted_at)
                            {{ $submission->submitted_at->format('d/m/Y H:i') }}
                        @else
                            <span class="text-yellow-600">Đang làm</span>
                        @endif
                    </td>
                    
                    <td class="px-4 py-4 text-center text-sm text-gray-600">
                        @if($submission->time_spent)
                            {{ floor($submission->time_spent / 60) }} phút {{ $submission->time_spent % 60 }} giây
                        @else
                            N/A
                        @endif
                    </td>
                    
                    <td class="px-4 py-4 text-center">
                        @if($submission->score !== null)
                            <span class="text-lg font-bold text-blue-600">
                                {{ number_format($submission->score, 2) }}
                            </span>
                            <span class="text-sm text-gray-500">/ {{ $exam->total_points }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    
                    <td class="px-4 py-4 text-center">
                        @if($submission->grading_status === 'graded')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                Đã chấm
                            </span>
                        @elseif($submission->grading_status === 'auto_graded')
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                Chấm tự động
                            </span>
                        @elseif($submission->grading_status === 'pending' && $submission->status === 'submitted')
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                Chờ chấm
                            </span>
                        @elseif($submission->status === 'in_progress')
                            <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">
                                Đang làm
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                {{ ucfirst($submission->grading_status) }}
                            </span>
                        @endif
                    </td>
                    
                    <td class="px-4 py-4 text-center text-sm text-gray-600">
                        {{ $submission->grader ? $submission->grader->name : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-gray-500">
                        <i data-feather="inbox" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <p class="text-sm">Chưa có bài nộp nào</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($submissions->hasPages())
<div class="mt-6 flex justify-center">
    {{ $submissions->links() }}
</div>
@endif

@endsection
