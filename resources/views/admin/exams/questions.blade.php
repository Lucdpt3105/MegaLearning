@extends('admin.layout')

@section('title', 'Câu hỏi trong đề thi')
@section('page-title', 'Câu hỏi: ' . $exam->title)
@section('page-description', 'Danh sách câu hỏi trong đề thi')

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
<div class="grid grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Tổng câu hỏi</div>
        <div class="text-3xl font-bold text-gray-900">{{ $exam->questions->count() }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Tổng điểm</div>
        <div class="text-3xl font-bold text-blue-600">{{ $exam->total_points }}</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="text-sm text-gray-600 mb-1">Thời gian làm bài</div>
        <div class="text-3xl font-bold text-green-600">{{ $exam->duration }} <span class="text-lg text-gray-600">phút</span></div>
    </div>
</div>

<!-- Questions List -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Danh sách câu hỏi ({{ $exam->questions->count() }} câu)</h3>
    
    @if($exam->questions->isEmpty())
        <div class="text-center py-12">
            <i data-feather="file-text" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có câu hỏi</h3>
            <p class="text-gray-600">Đề thi này chưa có câu hỏi nào</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach($exam->questions->sortBy('pivot.order') as $index => $question)
                <div class="border border-gray-200 rounded-lg p-6 hover:border-blue-300 transition-colors">
                    <div class="flex items-start gap-4">
                        <!-- Question Number -->
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-lg">
                                {{ $index + 1 }}
                            </div>
                        </div>
                        
                        <!-- Question Content -->
                        <div class="flex-1">
                            <!-- Question Meta -->
                            <div class="flex items-center gap-3 mb-3">
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                    {{ ucfirst($question->type) }}
                                </span>
                                
                                @if($question->topic)
                                <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700">
                                    {{ $question->topic->name }}
                                </span>
                                @endif
                                
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                    {{ $question->pivot->points }} điểm
                                </span>
                                
                                @if($question->difficulty)
                                <span class="px-3 py-1 text-xs rounded-full 
                                    {{ $question->difficulty === 'easy' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $question->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $question->difficulty === 'hard' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst($question->difficulty) }}
                                </span>
                                @endif
                            </div>
                            
                            <!-- Question Text -->
                            <div class="text-base text-gray-900 mb-4 leading-relaxed">
                                {!! nl2br(e($question->content)) !!}
                            </div>
                            
                            <!-- Multiple Choice Answers -->
                            @if($question->type === 'multiple_choice' && $question->answers)
                                <div class="space-y-2 pl-4">
                                    @foreach($question->answers->sortBy('order') as $key => $answer)
                                        <div class="flex items-start gap-3 p-3 rounded-lg
                                            {{ $answer->is_correct ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                                            <span class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-sm font-semibold
                                                {{ $answer->is_correct ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-700' }}">
                                                {{ chr(65 + $key) }}
                                            </span>
                                            <span class="flex-1 text-sm {{ $answer->is_correct ? 'font-medium text-green-900' : 'text-gray-700' }}">
                                                {{ $answer->content }}
                                            </span>
                                            @if($answer->is_correct)
                                                <i data-feather="check-circle" class="w-5 h-5 text-green-600"></i>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            
                            <!-- Essay Question -->
                            @if($question->type === 'essay')
                                <div class="p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                                    <p class="text-sm text-blue-900">
                                        <i data-feather="edit-3" class="w-4 h-4 inline"></i>
                                        Câu hỏi tự luận - Học sinh sẽ nhập câu trả lời
                                    </p>
                                </div>
                            @endif
                            
                            <!-- Explanation -->
                            @if($question->explanation)
                                <div class="mt-4 p-4 bg-indigo-50 rounded-lg border-l-4 border-indigo-500">
                                    <p class="text-sm font-semibold text-indigo-900 mb-1">
                                        <i data-feather="info" class="w-4 h-4 inline"></i>
                                        Giải thích:
                                    </p>
                                    <p class="text-sm text-indigo-800">{{ $question->explanation }}</p>
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
