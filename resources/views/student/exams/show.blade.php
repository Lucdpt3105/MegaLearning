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
    <div class="bg-white rounded-xl shadow-lg p-8 mb-6 border-2 border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold text-gray-900">{{ $exam->title }}</h1>
            @if($exam->is_ongoing)
                <span class="px-4 py-2 bg-green-600 text-white font-bold rounded-lg shadow-sm">Đang Diễn Ra</span>
            @elseif($exam->is_upcoming)
                <span class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg shadow-sm">Sắp Tới</span>
            @else
                <span class="px-4 py-2 bg-gray-600 text-white font-bold rounded-lg shadow-sm">Đã Kết Thúc</span>
            @endif
        </div>

        <p class="text-gray-700 mb-6 font-medium">{{ $exam->description }}</p>

        <!-- Exam Info Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-4 rounded-xl border-2 border-indigo-200">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="text-indigo-600 text-xs font-bold">Môn Học</div>
                </div>
                <div class="font-bold text-gray-900 text-lg">{{ $exam->subject ? $exam->subject->name : 'Chưa có môn học' }}</div>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl border-2 border-purple-200">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="text-purple-600 text-xs font-bold">Lớp Học</div>
                </div>
                <div class="font-bold text-gray-900 text-lg">{{ $exam->classRoom->name }}</div>
            </div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border-2 border-blue-200">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-blue-600 text-xs font-bold">Thời Gian</div>
                </div>
                <div class="font-bold text-gray-900 text-lg">{{ $exam->duration }} phút</div>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl border-2 border-green-200">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="text-green-600 text-xs font-bold">Tổng Số Câu</div>
                </div>
                <div class="font-bold text-gray-900 text-lg">{{ $exam->questions->count() }} câu</div>
            </div>
            <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-4 rounded-xl border-2 border-amber-200">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-amber-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="text-amber-600 text-xs font-bold">Điểm Tối Đa</div>
                </div>
                <div class="font-bold text-gray-900 text-lg">{{ $exam->total_points }} điểm</div>
            </div>
            <div class="bg-gradient-to-br from-rose-50 to-rose-100 p-4 rounded-xl border-2 border-rose-200">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-rose-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-rose-600 text-xs font-bold">Điểm Đạt</div>
                </div>
                <div class="font-bold text-gray-900 text-lg">≥ {{ $exam->passing_score }} điểm</div>
            </div>
        </div>

        <!-- Time Info -->
        @if($exam->start_time && $exam->end_time)
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-2 border-blue-300 rounded-xl p-4 mb-6">
                <div class="flex items-center text-blue-900">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-base">Thời gian làm bài</div>
                        <div class="text-sm font-semibold mt-1">{{ $exam->start_time->format('d/m/Y H:i') }} - {{ $exam->end_time->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gradient-to-r from-green-50 to-green-100 border-2 border-green-300 rounded-xl p-4 mb-6">
                <div class="flex items-center text-green-900">
                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold text-base">Không giới hạn thời gian</div>
                        <div class="text-sm font-semibold mt-1">Có thể làm bài bất kỳ lúc nào</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Submission History -->
        @if($exam->submissions->isNotEmpty())
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg">Lịch Sử Làm Bài</h3>
                </div>
                <div class="space-y-2">
                    @foreach($exam->submissions as $submission)
                        <div class="flex items-center justify-between bg-gradient-to-r from-gray-50 to-gray-100 p-4 rounded-xl border-2 border-gray-200 hover:border-indigo-300 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <span class="font-bold text-indigo-600">#{{ $submission->attempt_number }}</span>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">Lần {{ $submission->attempt_number }}</div>
                                    <div class="text-gray-600 text-sm font-medium">{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : 'Chưa nộp' }}</div>
                                </div>
                            </div>
                            <div>
                                @if($submission->grading_status === 'graded' || $submission->grading_status === 'auto_graded')
                                    <div class="text-right">
                                        <div class="font-bold text-lg {{ $submission->score >= $exam->passing_score ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($submission->score, 1) }}/{{ $exam->total_points }}
                                        </div>
                                        <span class="inline-block px-2 py-1 rounded-md text-xs font-bold {{ $submission->score >= $exam->passing_score ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $submission->score >= $exam->passing_score ? 'Đạt' : 'Chưa đạt' }}
                                        </span>
                                    </div>
                                @else
                                    <span class="inline-block px-3 py-1.5 bg-yellow-100 text-yellow-700 font-bold text-sm rounded-lg">Đang chờ chấm</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Instructions -->
        <div class="bg-gradient-to-br from-amber-50 to-yellow-50 border-2 border-amber-300 rounded-xl p-5 mb-6">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 bg-amber-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-amber-900 text-lg">Hướng Dẫn</h3>
            </div>
            <ul class="space-y-2 text-amber-900">
                <li class="flex items-start gap-2">
                    <span class="text-amber-600 font-bold mt-0.5">•</span>
                    <span class="font-medium">Đọc kỹ đề trước khi làm bài</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-amber-600 font-bold mt-0.5">•</span>
                    <span class="font-medium">Thời gian làm bài: {{ $exam->duration }} phút</span>
                </li>
                @if($exam->shuffle_questions)
                    <li class="flex items-start gap-2">
                        <span class="text-amber-600 font-bold mt-0.5">•</span>
                        <span class="font-medium">Thứ tự câu hỏi sẽ được xáo trộn</span>
                    </li>
                @endif
                @if($exam->shuffle_answers)
                    <li class="flex items-start gap-2">
                        <span class="text-amber-600 font-bold mt-0.5">•</span>
                        <span class="font-medium">Thứ tự đáp án sẽ được xáo trộn</span>
                    </li>
                @endif
                @if(!$exam->allow_retake)
                    <li class="flex items-start gap-2 bg-red-100 border-2 border-red-300 rounded-lg p-2 -ml-2">
                        <span class="text-red-600 font-bold mt-0.5">⚠️</span>
                        <span class="text-red-700 font-bold">Chỉ được làm 1 lần duy nhất</span>
                    </li>
                @elseif($exam->max_attempts > 0)
                    <li class="flex items-start gap-2">
                        <span class="text-amber-600 font-bold mt-0.5">•</span>
                        <span class="font-medium">Được làm tối đa {{ $exam->max_attempts }} lần</span>
                    </li>
                @endif
                @if($exam->show_results_immediately)
                    <li class="flex items-start gap-2">
                        <span class="text-amber-600 font-bold mt-0.5">•</span>
                        <span class="font-medium">Kết quả sẽ hiển thị ngay sau khi nộp bài</span>
                    </li>
                @endif
                @if($exam->require_access_code)
                    <li class="flex items-start gap-2 bg-red-100 border-2 border-red-300 rounded-lg p-2 -ml-2">
                        <span class="text-red-600 font-bold mt-0.5">🔒</span>
                        <span class="text-red-700 font-bold">Yêu cầu mã truy cập để làm bài</span>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4">
            <a href="{{ route('student.exams.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gray-600 text-white rounded-xl hover:bg-gray-700 transition-all hover:shadow-lg font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Quay Lại
            </a>
            
            @if($exam->can_take)
                <form action="{{ route('student.exams.take', $exam->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" 
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all hover:shadow-lg font-bold text-lg"
                            onclick="return confirm('Bạn đã sẵn sàng bắt đầu làm bài?')">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Bắt Đầu Làm Bài
                    </button>
                </form>
            @elseif($exam->is_upcoming)
                <button disabled class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-400 text-white rounded-xl cursor-not-allowed font-bold text-lg opacity-60">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Chưa Đến Giờ Làm Bài
                </button>
            @elseif($exam->is_finished)
                <button disabled class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-400 text-white rounded-xl cursor-not-allowed font-bold text-lg opacity-60">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Đã Hết Hạn
                </button>
            @elseif(!$exam->allow_retake && $exam->submission_count > 0)
                <button disabled class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-400 text-white rounded-xl cursor-not-allowed font-bold text-lg opacity-60">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Đã Làm Bài Rồi
                </button>
            @else
                <button disabled class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-400 text-white rounded-xl cursor-not-allowed font-bold text-lg opacity-60">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Không Thể Làm Bài
                </button>
            @endif
        </div>
    </div>
</div>
@endsection
