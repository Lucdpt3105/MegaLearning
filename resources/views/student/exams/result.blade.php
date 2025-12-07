@extends('layouts.exam')

@section('title', 'Kết Quả Bài Làm - MegaLearning')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <!-- Result Header -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
        <div class="text-center mb-6">
            @if($submission->score >= $exam->passing_score)
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                    <svg class="w-12 h-12 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-green-600 mb-2">Chúc Mừng! Bạn Đã Đạt</h1>
            @else
                <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                    <svg class="w-12 h-12 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-red-600 mb-2">Chưa Đạt Yêu Cầu</h1>
            @endif
            <p class="text-gray-600">{{ $exam->title }}</p>
        </div>

        <!-- Score Display -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-blue-50 p-6 rounded-lg text-center">
                <div class="text-4xl font-bold text-blue-600 mb-2">{{ number_format($submission->score ?? 0, 1) }}</div>
                <div class="text-sm text-gray-600">Điểm Của Bạn</div>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg text-center">
                <div class="text-4xl font-bold text-gray-900 mb-2">{{ $exam->total_points }}</div>
                <div class="text-sm text-gray-600">Điểm Tối Đa</div>
            </div>
            <div class="bg-purple-50 p-6 rounded-lg text-center">
                <div class="text-4xl font-bold text-purple-600 mb-2">{{ $exam->total_points > 0 ? number_format(($submission->score ?? 0 / $exam->total_points) * 100, 1) : 0 }}%</div>
                <div class="text-sm text-gray-600">Phần Trăm</div>
            </div>
            <div class="bg-green-50 p-6 rounded-lg text-center">
                <div class="text-4xl font-bold text-green-600 mb-2">{{ $exam->passing_score }}</div>
                <div class="text-sm text-gray-600">Điểm Đạt</div>
            </div>
        </div>
        
        @php
            $mcQuestions = $questions->where('type', 'multiple_choice');
            $correctCount = $mcQuestions->where('is_correct', true)->count();
            $totalMC = $mcQuestions->count();
        @endphp
        
        @if($totalMC > 0)
            <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">📊 Thống Kê Trắc Nghiệm</h3>
                        <p class="text-sm text-gray-600">Số câu trả lời đúng / Tổng số câu trắc nghiệm</p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-blue-600">{{ $correctCount }}/{{ $totalMC }}</div>
                        <div class="text-sm text-gray-600">{{ $totalMC > 0 ? number_format(($correctCount / $totalMC) * 100, 1) : 0 }}% đúng</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Additional Info -->
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div class="flex justify-between p-3 bg-gray-50 rounded">
                <span class="text-gray-600">Thời gian làm bài:</span>
                <span class="font-medium">{{ gmdate('i:s', $submission->time_spent) }}</span>
            </div>
            <div class="flex justify-between p-3 bg-gray-50 rounded">
                <span class="text-gray-600">Lần làm:</span>
                <span class="font-medium">Lần {{ $submission->attempt_number }}</span>
            </div>
            <div class="flex justify-between p-3 bg-gray-50 rounded">
                <span class="text-gray-600">Nộp bài lúc:</span>
                <span class="font-medium">{{ $submission->submitted_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between p-3 bg-gray-50 rounded">
                <span class="text-gray-600">Trạng thái chấm:</span>
                <span class="font-medium">
                    @if($submission->grading_status === 'auto_graded')
                        Tự động chấm
                    @elseif($submission->grading_status === 'graded')
                        Đã chấm bởi GV
                    @else
                        Đang chờ
                    @endif
                </span>
            </div>
        </div>

        @if($submission->feedback)
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h3 class="font-semibold text-yellow-900 mb-2">💬 Nhận Xét Của Giáo Viên</h3>
                <p class="text-yellow-800">{{ $submission->feedback }}</p>
            </div>
        @endif
    </div>

    <!-- Review Answers -->
    @if($exam->allow_review)
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Chi Tiết Bài Làm</h2>
            
            <div class="space-y-6">
                @foreach($questions as $index => $question)
                    <div class="border-l-4 {{ isset($question->is_correct) ? ($question->is_correct ? 'border-green-500' : 'border-red-500') : 'border-gray-300' }} pl-6 py-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-10 h-10 {{ isset($question->is_correct) ? ($question->is_correct ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600') : 'bg-gray-100 text-gray-600' }} rounded-full flex items-center justify-center font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-3">
                                    <p class="text-lg font-medium text-gray-900">
                                        {{ $question->pivot->custom_content ?? $question->content }}
                                    </p>
                                    <div class="ml-4 text-sm flex-shrink-0">
                                        @if(isset($question->is_correct))
                                            @if($question->is_correct)
                                                <div class="text-right">
                                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full font-medium">✓ Đúng</span>
                                                    <div class="mt-1 text-green-600 font-bold">+{{ $question->pivot->points ?? 1 }} điểm</div>
                                                </div>
                                            @else
                                                <div class="text-right">
                                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full font-medium">✗ Sai</span>
                                                    <div class="mt-1 text-red-600 font-bold">0 điểm</div>
                                                </div>
                                            @endif
                                        @else
                                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full">{{ $question->pivot->points ?? 1 }} điểm</span>
                                        @endif
                                    </div>
                                </div>

                                @if($question->type === 'multiple_choice')
                                    <div class="space-y-2 mb-3">
                                        @foreach($question->answers as $answer)
                                            <div class="p-3 rounded-lg border-2 
                                                {{ $answer->is_correct ? 'border-green-500 bg-green-50' : 'border-gray-200' }}
                                                {{ $question->student_answer == $answer->id && !$answer->is_correct ? 'border-red-500 bg-red-50' : '' }}">
                                                <div class="flex items-center">
                                                    @if($answer->is_correct)
                                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    @elseif($question->student_answer == $answer->id)
                                                        <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    @else
                                                        <span class="w-5 h-5 mr-2"></span>
                                                    @endif
                                                    <span class="{{ $answer->is_correct ? 'font-medium text-green-900' : ($question->student_answer == $answer->id ? 'font-medium text-red-900' : 'text-gray-700') }}">
                                                        {{ $answer->content }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif($question->type === 'essay')
                                    <div class="mb-3">
                                        <div class="text-sm font-medium text-gray-700 mb-2">Câu trả lời của bạn:</div>
                                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                            @if($question->student_answer)
                                                <div class="text-gray-900">{{ $question->student_answer }}</div>
                                            @else
                                                <span class="text-gray-400 italic">Chưa trả lời</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($question->explanation)
                                    <div class="mt-3 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                        <div class="text-sm font-medium text-blue-900 mb-1">💡 Giải thích:</div>
                                        <div class="text-sm text-blue-800">{{ $question->explanation }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-600">Giáo viên không cho phép xem lại chi tiết bài làm</p>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="mt-6 flex gap-4">
        <a href="{{ route('student.exams.index') }}" 
           class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-center font-medium">
            ← Quay Về Danh Sách
        </a>
        @if($exam->allow_retake && ($exam->max_attempts == 0 || $submission->attempt_number < $exam->max_attempts))
            <a href="{{ route('student.exams.show', $exam->id) }}" 
               class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-center font-medium">
                🔄 Làm Lại
            </a>
        @endif
    </div>
</div>
@endsection
