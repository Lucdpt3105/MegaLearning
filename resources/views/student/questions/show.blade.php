@extends('layouts.app')

@section('title', 'Chi tiết câu hỏi')

@section('content')
<div class="container-fluid p-6">
    <!-- Back Button -->
    <div class="mb-4">
        <a 
            href="{{ route('student.questions.browse') }}"
            class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition-colors"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Quay lại danh sách
        </a>
    </div>

    <!-- Question Card -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
        <!-- Tags -->
        <div class="flex items-center gap-2 mb-6">
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                📚 {{ $question->subject ? $question->subject->name : 'N/A' }}
            </span>
            @if($question->topic)
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                    🏷️ {{ $question->topic->name }}
                </span>
            @endif
            @if($question->difficulty_level)
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium 
                    {{ $question->difficulty_level === 'easy' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $question->difficulty_level === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $question->difficulty_level === 'hard' ? 'bg-red-100 text-red-800' : '' }}
                ">
                    {{ $question->difficulty_level === 'easy' ? '⭐ Dễ' : '' }}
                    {{ $question->difficulty_level === 'medium' ? '⭐⭐ Trung bình' : '' }}
                    {{ $question->difficulty_level === 'hard' ? '⭐⭐⭐ Khó' : '' }}
                </span>
            @endif
        </div>

        <!-- Question Text -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Câu hỏi:</h2>
            <div class="bg-gray-50 rounded-xl p-6 border-l-4 border-indigo-600">
                <p class="text-lg text-gray-800">{{ $question->question_text }}</p>
            </div>
        </div>

        <!-- Answers -->
        @if($question->answers && $question->answers->count() > 0)
            <div>
                <h3 class="text-xl font-bold text-gray-800 mb-4">Các đáp án:</h3>
                <div class="space-y-3">
                    @foreach($question->answers as $index => $answer)
                        <div class="flex items-start p-4 rounded-xl border-2 transition-all duration-200
                            {{ $answer->is_correct 
                                ? 'bg-green-50 border-green-500' 
                                : 'bg-gray-50 border-gray-200 hover:border-gray-300' 
                            }}
                        ">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg
                                {{ $answer->is_correct 
                                    ? 'bg-green-500 text-white' 
                                    : 'bg-gray-300 text-gray-700' 
                                }}
                            ">
                                {{ chr(65 + $index) }}
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-gray-800 text-lg">{{ $answer->answer_text }}</p>
                                @if($answer->is_correct)
                                    <div class="flex items-center mt-2 text-green-700 text-sm font-medium">
                                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Đáp án đúng
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
