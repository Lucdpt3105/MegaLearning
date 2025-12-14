@extends('admin.layout')

@section('title', 'Chi tiết bài làm')
@section('page-title', 'Chi tiết bài làm')
@section('page-description', 'Xem chi tiết bài làm của học sinh')

@section('content')

<!-- Header Actions -->
<div class="mb-6 flex items-center justify-between">
    <div>
        <h3 class="text-lg font-semibold text-gray-900">{{ $submission->exam->title }}</h3>
        <p class="text-sm text-gray-600">
            Học sinh: {{ $submission->student ? $submission->student->name : 'N/A' }}
        </p>
    </div>
    
    <a href="{{ route('admin.exam-results.index') }}" 
       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center gap-2">
        <i data-feather="arrow-left" class="w-5 h-5"></i>
        Quay lại
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Điểm số</div>
        <div class="text-3xl font-bold text-blue-600">
            {{ $submission->score !== null ? number_format($submission->score, 2) : 'N/A' }}
            <span class="text-lg text-gray-600">/10</span>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Thời gian làm</div>
        <div class="text-3xl font-bold text-gray-900">
            @if($submission->time_spent)
                {{ floor($submission->time_spent / 60) }}
                <span class="text-lg text-gray-600">phút</span>
            @else
                N/A
            @endif
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Lần thi</div>
        <div class="text-3xl font-bold text-gray-900">{{ $submission->attempt_number }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Trạng thái</div>
        <div class="mt-2">
            @if($submission->grading_status === 'graded')
                <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">
                    Đã chấm
                </span>
            @elseif($submission->grading_status === 'auto_graded')
                <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800">
                    Chấm tự động
                </span>
            @else
                <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">
                    Chờ chấm
                </span>
            @endif
        </div>
    </div>
</div>

<!-- Student Info & Grading Info -->
<div class="grid grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin học sinh</h3>
        <div class="space-y-3">
            <div>
                <div class="text-sm text-gray-600">Họ tên</div>
                <div class="text-base font-medium text-gray-900">
                    {{ $submission->student ? $submission->student->name : 'N/A' }}
                </div>
            </div>
            <div>
                <div class="text-sm text-gray-600">Email</div>
                <div class="text-base text-gray-900">
                    {{ $submission->student ? $submission->student->email : 'N/A' }}
                </div>
            </div>
            <div>
                <div class="text-sm text-gray-600">Thời gian nộp</div>
                <div class="text-base text-gray-900">
                    {{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : 'N/A' }}
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin chấm điểm</h3>
        <div class="space-y-3">
            <div>
                <div class="text-sm text-gray-600">Người chấm</div>
                <div class="text-base font-medium text-gray-900">
                    {{ $submission->grader ? $submission->grader->name : 'Hệ thống' }}
                </div>
            </div>
            <div>
                <div class="text-sm text-gray-600">Thời gian chấm</div>
                <div class="text-base text-gray-900">
                    {{ $submission->graded_at ? $submission->graded_at->format('d/m/Y H:i') : 'N/A' }}
                </div>
            </div>
            @if($submission->feedback)
            <div>
                <div class="text-sm text-gray-600">Nhận xét</div>
                <div class="text-base text-gray-900">{{ $submission->feedback }}</div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Questions and Answers -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Câu trả lời của học sinh</h3>
    
    @if($submission->exam->questions->isEmpty())
        <div class="text-center py-12">
            <i data-feather="file-text" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Không có câu hỏi</h3>
            <p class="text-gray-600">Đề thi này không có câu hỏi</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach($submission->exam->questions->sortBy('pivot.order') as $index => $question)
                <div class="border border-gray-200 rounded-lg p-6">
                    <div class="flex items-start gap-4">
                        <!-- Question Number -->
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-lg">
                                {{ $index + 1 }}
                            </div>
                        </div>
                        
                        <!-- Question Content -->
                        <div class="flex-1">
                            <!-- Question Text -->
                            <div class="text-base text-gray-900 mb-4 leading-relaxed font-medium">
                                {!! nl2br(e($question->content)) !!}
                            </div>
                            
                            <!-- Multiple Choice Answer -->
                            @if($question->type === 'multiple_choice' && $question->answers)
                                @php
                                    $studentAnswerId = $submission->answers[$question->id] ?? null;
                                    $correctAnswer = $question->answers->where('is_correct', true)->first();
                                @endphp
                                
                                <div class="space-y-2 pl-4">
                                    @foreach($question->answers->sortBy('order') as $key => $answer)
                                        @php
                                            $isStudentAnswer = $studentAnswerId == $answer->id;
                                            $isCorrect = $answer->is_correct;
                                        @endphp
                                        
                                        <div class="flex items-start gap-3 p-3 rounded-lg
                                            {{ $isCorrect ? 'bg-green-50 border border-green-200' : '' }}
                                            {{ $isStudentAnswer && !$isCorrect ? 'bg-red-50 border border-red-200' : '' }}
                                            {{ !$isStudentAnswer && !$isCorrect ? 'bg-gray-50' : '' }}">
                                            <span class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-sm font-semibold
                                                {{ $isCorrect ? 'bg-green-500 text-white' : '' }}
                                                {{ $isStudentAnswer && !$isCorrect ? 'bg-red-500 text-white' : '' }}
                                                {{ !$isStudentAnswer && !$isCorrect ? 'bg-gray-300 text-gray-700' : '' }}">
                                                {{ chr(65 + $key) }}
                                            </span>
                                            <span class="flex-1 text-sm {{ $isCorrect || $isStudentAnswer ? 'font-medium' : '' }}">
                                                {{ $answer->content }}
                                            </span>
                                            @if($isCorrect)
                                                <i data-feather="check-circle" class="w-5 h-5 text-green-600"></i>
                                            @endif
                                            @if($isStudentAnswer && !$isCorrect)
                                                <i data-feather="x-circle" class="w-5 h-5 text-red-600"></i>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            
                            <!-- Essay Answer -->
                            @if($question->type === 'essay')
                                <div class="p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                                    <p class="text-sm font-semibold text-blue-900 mb-2">Câu trả lời:</p>
                                    <p class="text-sm text-blue-800">
                                        {{ $submission->answers[$question->id] ?? 'Chưa trả lời' }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
