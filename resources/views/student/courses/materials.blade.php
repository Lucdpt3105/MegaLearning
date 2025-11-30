@extends('layouts.app')

@section('title', 'Tài Liệu - ' . $classRoom->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('student.courses.index') }}" class="hover:text-indigo-600">Khóa Học Của Tôi</a></li>
            <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
            <li><a href="{{ route('student.courses.show', $classRoom->id) }}" class="hover:text-indigo-600">{{ $classRoom->name }}</a></li>
            <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
            <li class="text-gray-900 font-semibold">Tài Liệu</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Tài Liệu Học Tập</h1>
        <p class="text-gray-600">{{ $classRoom->name }} - {{ $classRoom->subject->name }}</p>
    </div>

    <!-- Navigation Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('student.courses.show', $classRoom->id) }}" class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Tổng Quan
            </a>
            <a href="{{ route('student.courses.materials', $classRoom->id) }}" class="border-b-2 border-indigo-500 py-4 px-1 text-sm font-medium text-indigo-600">
                Tài Liệu
            </a>
            <a href="{{ route('student.courses.schedule', $classRoom->id) }}" class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Lịch Học
            </a>
        </nav>
    </div>

    <!-- Topics List -->
    @if($topics->isEmpty())
        <div class="bg-white rounded-xl shadow-md p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có tài liệu</h3>
            <p class="text-gray-600">Giảng viên chưa thêm tài liệu học tập cho khóa học này.</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach($topics as $index => $topic)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- Topic Header -->
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="bg-white bg-opacity-20 rounded-full w-12 h-12 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-white">{{ $index + 1 }}</span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">{{ $topic->name }}</h3>
                                    <p class="text-indigo-100 text-sm">{{ $topic->questions_count }} câu hỏi</p>
                                </div>
                            </div>
                            <button 
                                onclick="toggleTopic({{ $topic->id }})"
                                class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors duration-200"
                            >
                                <svg id="icon-{{ $topic->id }}" class="w-6 h-6 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Topic Content (Collapsible) -->
                    <div id="content-{{ $topic->id }}" class="hidden">
                        <div class="p-6">
                            @if($topic->questions->isEmpty())
                                <p class="text-gray-600 text-center py-8">Chưa có câu hỏi nào trong chủ đề này.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($topic->questions as $qIndex => $question)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 font-semibold text-sm">
                                                        {{ $qIndex + 1 }}
                                                    </span>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-gray-900 font-medium mb-2">{!! nl2br(e($question->content)) !!}</p>
                                                    
                                                    <!-- Question Metadata -->
                                                    <div class="flex items-center space-x-4 text-xs text-gray-500 mb-3">
                                                        <span class="flex items-center">
                                                            @if($question->difficulty == 'easy')
                                                                <span class="w-2 h-2 rounded-full bg-green-500 mr-1"></span> Dễ
                                                            @elseif($question->difficulty == 'medium')
                                                                <span class="w-2 h-2 rounded-full bg-yellow-500 mr-1"></span> Trung bình
                                                            @else
                                                                <span class="w-2 h-2 rounded-full bg-red-500 mr-1"></span> Khó
                                                            @endif
                                                        </span>
                                                        @if($question->points)
                                                            <span>{{ $question->points }} điểm</span>
                                                        @endif
                                                    </div>

                                                    <!-- Answers -->
                                                    @if($question->answers->isNotEmpty())
                                                        <div class="space-y-2">
                                                            @foreach($question->answers as $aIndex => $answer)
                                                                <div class="flex items-start space-x-2 text-sm
                                                                    @if($answer->is_correct) 
                                                                        bg-green-50 border border-green-200 rounded px-3 py-2
                                                                    @else
                                                                        text-gray-700
                                                                    @endif
                                                                ">
                                                                    <span class="font-semibold">{{ chr(65 + $aIndex) }}.</span>
                                                                    <span class="flex-1">{{ $answer->content }}</span>
                                                                    @if($answer->is_correct)
                                                                        <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
function toggleTopic(topicId) {
    const content = document.getElementById(`content-${topicId}`);
    const icon = document.getElementById(`icon-${topicId}`);
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}
</script>
@endsection
