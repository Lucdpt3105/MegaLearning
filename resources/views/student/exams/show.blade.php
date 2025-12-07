@extends('layouts.exam')

@section('title', $exam->title . ' - MegaLearning')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            {{ session('error') }}
        </div>
    @endif

    <!-- Exam Header -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-gray-900">{{ $exam->title }}</h1>
            @if($exam->is_ongoing)
                <span class="px-4 py-2 bg-green-100 text-green-800 font-semibold rounded-full">Đang Diễn Ra</span>
            @elseif($exam->is_upcoming)
                <span class="px-4 py-2 bg-blue-100 text-blue-800 font-semibold rounded-full">Sắp Tới</span>
            @else
                <span class="px-4 py-2 bg-gray-100 text-gray-800 font-semibold rounded-full">Đã Kết Thúc</span>
            @endif
        </div>

        <p class="text-gray-600 mb-6">{{ $exam->description }}</p>

        <!-- Exam Info Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="text-gray-500 text-sm mb-1">Môn Học</div>
                <div class="font-semibold text-gray-900">{{ $exam->subject->name }}</div>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="text-gray-500 text-sm mb-1">Lớp Học</div>
                <div class="font-semibold text-gray-900">{{ $exam->classRoom->name }}</div>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="text-gray-500 text-sm mb-1">Thời Gian Làm Bài</div>
                <div class="font-semibold text-gray-900">{{ $exam->duration }} phút</div>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="text-gray-500 text-sm mb-1">Tổng Số Câu</div>
                <div class="font-semibold text-gray-900">{{ $exam->total_questions }} câu</div>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="text-gray-500 text-sm mb-1">Điểm Tối Đa</div>
                <div class="font-semibold text-gray-900">{{ $exam->total_points }} điểm</div>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="text-gray-500 text-sm mb-1">Điểm Đạt</div>
                <div class="font-semibold text-gray-900">≥ {{ $exam->passing_score }} điểm</div>
            </div>
        </div>

        <!-- Time Info -->
        @if($exam->start_time && $exam->end_time)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center text-blue-800">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <div class="font-medium">Thời gian làm bài</div>
                        <div class="text-sm">{{ $exam->start_time->format('d/m/Y H:i') }} - {{ $exam->end_time->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center text-green-800">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <div class="font-medium">Không giới hạn thời gian</div>
                        <div class="text-sm">Có thể làm bài bất kỳ lúc nào</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Submission History -->
        @if($exam->submissions->isNotEmpty())
            <div class="mb-6">
                <h3 class="font-semibold text-gray-900 mb-3">Lịch Sử Làm Bài</h3>
                <div class="space-y-2">
                    @foreach($exam->submissions as $submission)
                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                            <div>
                                <span class="font-medium">Lần {{ $submission->attempt_number }}</span>
                                <span class="text-gray-500 text-sm ml-2">{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : 'Chưa nộp' }}</span>
                            </div>
                            <div>
                                @if($submission->grading_status === 'graded' || $submission->grading_status === 'auto_graded')
                                    <span class="font-semibold {{ $submission->score >= $exam->passing_score ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($submission->score, 1) }}/{{ $exam->total_points }}
                                    </span>
                                @else
                                    <span class="text-gray-500">Đang chờ chấm</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Instructions -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-yellow-900 mb-2">📋 Hướng Dẫn</h3>
            <ul class="list-disc list-inside text-yellow-800 text-sm space-y-1">
                <li>Đọc kỹ đề trước khi làm bài</li>
                <li>Thời gian làm bài: {{ $exam->duration }} phút</li>
                @if($exam->shuffle_questions)
                    <li>Thứ tự câu hỏi sẽ được xáo trộn</li>
                @endif
                @if($exam->shuffle_answers)
                    <li>Thứ tự đáp án sẽ được xáo trộn</li>
                @endif
                @if(!$exam->allow_retake)
                    <li class="text-red-600 font-medium">⚠️ Chỉ được làm 1 lần duy nhất</li>
                @elseif($exam->max_attempts > 0)
                    <li>Được làm tối đa {{ $exam->max_attempts }} lần</li>
                @endif
                @if($exam->show_results_immediately)
                    <li>Kết quả sẽ hiển thị ngay sau khi nộp bài</li>
                @endif
                @if($exam->require_access_code)
                    <li class="text-red-600 font-medium">🔒 Yêu cầu mã truy cập để làm bài</li>
                @endif
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4">
            <a href="{{ route('student.exams.index') }}" 
               class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                ← Quay Lại
            </a>
            
            @if($exam->can_take)
                <form action="{{ route('student.exams.take', $exam->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" 
                            class="w-full px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium"
                            onclick="return confirm('Bạn đã sẵn sàng bắt đầu làm bài?')">
                        🚀 Bắt Đầu Làm Bài
                    </button>
                </form>
            @elseif($exam->is_upcoming)
                <button disabled class="flex-1 px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium">
                    Chưa Đến Giờ Làm Bài
                </button>
            @elseif($exam->is_finished)
                <button disabled class="flex-1 px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium">
                    Đã Hết Hạn
                </button>
            @elseif(!$exam->allow_retake && $exam->submission_count > 0)
                <button disabled class="flex-1 px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium">
                    Đã Làm Bài Rồi
                </button>
            @else
                <button disabled class="flex-1 px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium">
                    Không Thể Làm Bài
                </button>
            @endif
        </div>
    </div>
</div>
@endsection
