@extends('layouts.app')

@section('title', 'Tài Liệu - ' . $classRoom->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="{{ route('student.courses.index') }}" class="hover:text-indigo-600">Lớp Học Của Tôi</a></li>
            <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
            <li><a href="{{ route('student.courses.show', $classRoom->id) }}" class="hover:text-indigo-600">{{ $classRoom->name }}</a></li>
            <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
            <li class="text-gray-900 font-semibold">Tài Liệu</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Tài Liệu Học Tập</h1>
        <p class="text-gray-600">{{ $classRoom->name }} - {{ $classRoom->subject ? $classRoom->subject->name : 'N/A' }}</p>
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

    <!-- Documents Section -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 mb-4">
            <h2 class="text-2xl font-bold text-white flex items-center">
                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Tài Liệu Tham Khảo
            </h2>
            <p class="text-blue-100 mt-2">
                @if($documents && $documents->count() > 0)
                    {{ $documents->count() }} tài liệu có sẵn
                @else
                    Chưa có tài liệu
                @endif
            </p>
        </div>

        @if($documents && $documents->isNotEmpty())

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($documents as $document)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                        <div class="p-6">
                            <!-- File Icon -->
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center
                                    @if(str_contains($document->file_type, 'pdf')) bg-red-100
                                    @elseif(str_contains($document->file_type, 'word') || str_contains($document->file_type, 'document')) bg-blue-100
                                    @elseif(str_contains($document->file_type, 'presentation') || str_contains($document->file_type, 'powerpoint')) bg-orange-100
                                    @elseif(str_contains($document->file_type, 'spreadsheet') || str_contains($document->file_type, 'excel')) bg-green-100
                                    @else bg-gray-100
                                    @endif
                                ">
                                    @if(str_contains($document->file_type, 'pdf'))
                                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                        </svg>
                                    @elseif(str_contains($document->file_type, 'word') || str_contains($document->file_type, 'document'))
                                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                                        {{ $document->title }}
                                    </h3>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($document->description)
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $document->description }}</p>
                            @endif

                            <!-- Metadata -->
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $document->created_at->diffForHumans() }}
                                </span>
                                <span>{{ number_format($document->file_size / 1024, 0) }} KB</span>
                            </div>

                            <!-- Download Button -->
                            @if($document->fileExists())
                                <a href="{{ route('student.documents.download', $document->id) }}" 
                                   class="block w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-200 text-center group-hover:shadow-lg">
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Tải Xuống
                                </a>
                            @else
                                <button disabled 
                                   class="block w-full bg-gray-300 text-gray-500 font-medium py-2 px-4 rounded-lg cursor-not-allowed text-center">
                                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    File không tồn tại
                                </button>
                                <p class="text-xs text-red-500 mt-1 text-center">Vui lòng liên hệ giảng viên</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl shadow-md p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có tài liệu</h3>
                <p class="text-gray-600">Giảng viên chưa upload tài liệu học tập cho lớp học này.</p>
                <p class="text-sm text-gray-500 mt-2">Tài liệu sẽ được hiển thị sau khi giảng viên upload và quản trị viên phê duyệt.</p>
            </div>
        @endif
    </div>

    <!-- Topics List -->
    @if($topics->isNotEmpty())
        <div class="mb-8">
            <div class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl shadow-lg p-6 mb-4">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Chủ Đề & Câu Hỏi
                </h2>
                <p class="text-purple-100 mt-2">{{ $topics->count() }} chủ đề học tập</p>
            </div>
            
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
