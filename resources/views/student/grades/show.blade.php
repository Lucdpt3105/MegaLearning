@extends('layouts.exam')

@section('title', 'Chi Tiết Bài Làm - MegaLearning')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $exam->title }}</h1>
                <p class="text-gray-600 mt-1">{{ $exam->subject->name }} - {{ $exam->classRoom->name }}</p>
            </div>
            <div class="text-right">
                @if($stats['passed'])
                    <div class="px-6 py-3 bg-green-100 text-green-800 rounded-lg">
                        <div class="text-sm font-medium">Kết Quả</div>
                        <div class="text-2xl font-bold">ĐẠT</div>
                    </div>
                @else
                    <div class="px-6 py-3 bg-red-100 text-red-800 rounded-lg">
                        <div class="text-sm font-medium">Kết Quả</div>
                        <div class="text-2xl font-bold">CHƯA ĐẠT</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Score Summary -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg text-center">
                <div class="text-3xl font-bold text-blue-600">{{ number_format($submission->score, 1) }}</div>
                <div class="text-sm text-gray-600 mt-1">Điểm Đạt Được</div>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg text-center">
                <div class="text-3xl font-bold text-purple-600">{{ number_format($stats['accuracy'], 1) }}%</div>
                <div class="text-sm text-gray-600 mt-1">Độ Chính Xác</div>
            </div>
            <div class="bg-green-50 p-4 rounded-lg text-center">
                <div class="text-3xl font-bold text-green-600">{{ $stats['correct_count'] }}/{{ $stats['total_questions'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Câu Đúng</div>
            </div>
            <div class="bg-orange-50 p-4 rounded-lg text-center">
                <div class="text-3xl font-bold text-orange-600">{{ $stats['time_spent_minutes'] }}</div>
                <div class="text-sm text-gray-600 mt-1">Phút Làm Bài</div>
            </div>
        </div>

        <!-- Submission Info -->
        <div class="grid grid-cols-2 gap-4 text-sm border-t pt-4">
            <div class="flex justify-between">
                <span class="text-gray-600">Lần làm bài:</span>
                <span class="font-medium">Lần {{ $submission->attempt_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Nộp bài lúc:</span>
                <span class="font-medium">{{ $submission->submitted_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Trạng thái chấm:</span>
                <span class="font-medium">
                    @if($submission->grading_status === 'auto_graded')
                        Tự động chấm
                    @elseif($submission->grading_status === 'graded')
                        Chấm bởi {{ $submission->grader->name ?? 'GV' }}
                    @else
                        Đang chờ
                    @endif
                </span>
            </div>
            @if($submission->graded_at)
                <div class="flex justify-between">
                    <span class="text-gray-600">Chấm lúc:</span>
                    <span class="font-medium">{{ $submission->graded_at->format('d/m/Y H:i') }}</span>
                </div>
            @endif
        </div>

        <!-- Teacher Feedback -->
        @if($submission->feedback)
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h3 class="font-semibold text-yellow-900 mb-2">💬 Nhận Xét Của Giáo Viên</h3>
                <p class="text-yellow-800">{{ $submission->feedback }}</p>
            </div>
        @endif
    </div>

    <!-- Detailed Answers -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Chi Tiết Từng Câu Hỏi</h2>
        
        <div class="space-y-6">
            @foreach($questions as $index => $question)
                <div class="border-l-4 {{ isset($question->is_correct) ? ($question->is_correct ? 'border-green-500' : 'border-red-500') : 'border-gray-300' }} pl-6 py-4 bg-gray-50 rounded-r-lg">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 {{ isset($question->is_correct) ? ($question->is_correct ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600') : 'bg-gray-100 text-gray-600' }} rounded-full flex items-center justify-center font-bold text-lg">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <!-- Question Header -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <p class="text-lg font-medium text-gray-900 mb-2">
                                        {{ $question->pivot->custom_content ?? $question->content }}
                                    </p>
                                    <div class="flex gap-2">
                                        @if($question->difficulty)
                                            <span class="inline-block px-2 py-1 text-xs rounded
                                                {{ $question->difficulty === 'easy' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $question->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $question->difficulty === 'hard' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ $question->difficulty === 'easy' ? 'Dễ' : ($question->difficulty === 'medium' ? 'TB' : 'Khó') }}
                                            </span>
                                        @endif
                                        <span class="inline-block px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded">
                                            {{ $question->pivot->points ?? 1 }} điểm
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    @if(isset($question->is_correct))
                                        @if($question->is_correct)
                                            <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full font-semibold text-sm">
                                                ✓ Đúng
                                            </span>
                                        @else
                                            <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full font-semibold text-sm">
                                                ✗ Sai
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <!-- Multiple Choice Answers -->
                            @if($question->type === 'multiple_choice')
                                <div class="space-y-2 mb-4">
                                    @foreach($question->answers as $answer)
                                        <div class="p-3 rounded-lg border-2 transition-all
                                            {{ $answer->is_correct ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-white' }}
                                            {{ $question->student_answer == $answer->id && !$answer->is_correct ? 'border-red-500 bg-red-50' : '' }}">
                                            <div class="flex items-center gap-3">
                                                @if($answer->is_correct)
                                                    <div class="flex-shrink-0 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                @elseif($question->student_answer == $answer->id)
                                                    <div class="flex-shrink-0 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                @else
                                                    <div class="flex-shrink-0 w-6 h-6 border-2 border-gray-300 rounded-full"></div>
                                                @endif
                                                
                                                <span class="flex-1 {{ $answer->is_correct ? 'font-semibold text-green-900' : ($question->student_answer == $answer->id ? 'font-semibold text-red-900' : 'text-gray-700') }}">
                                                    {{ $answer->content }}
                                                </span>
                                                
                                                @if($answer->is_correct)
                                                    <span class="text-xs font-medium text-green-700">Đáp án đúng</span>
                                                @elseif($question->student_answer == $answer->id)
                                                    <span class="text-xs font-medium text-red-700">Bạn đã chọn</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if(!$question->student_answer)
                                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <span class="text-sm text-yellow-800">⚠️ Bạn đã bỏ qua câu này</span>
                                    </div>
                                @endif
                            @endif

                            <!-- Essay Answer -->
                            @if($question->type === 'essay')
                                <div class="space-y-3">
                                    <div>
                                        <div class="text-sm font-medium text-gray-700 mb-2">Câu trả lời của bạn:</div>
                                        <div class="p-4 bg-white border border-gray-300 rounded-lg">
                                            @if($question->student_answer)
                                                <div class="text-gray-900">{{ $question->student_answer }}</div>
                                            @else
                                                <span class="text-gray-400 italic">Chưa trả lời</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Explanation -->
                            @if($question->explanation || $question->pivot->custom_explanation)
                                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <div class="text-sm font-medium text-blue-900 mb-1">Giải thích:</div>
                                            <div class="text-sm text-blue-800">{{ $question->pivot->custom_explanation ?? $question->explanation }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex gap-4">
        <a href="{{ route('student.grades.index') }}" 
           class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-center font-medium">
            ← Quay Về Bảng Điểm
        </a>
        <a href="{{ route('student.exams.show', $exam->id) }}" 
           class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-center font-medium">
            Xem Bài Kiểm Tra
        </a>
    </div>
</div>
@endsection
