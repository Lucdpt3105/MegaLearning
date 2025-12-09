@extends('layouts.app')

@section('title', 'Kết quả tìm kiếm: ' . $query . ' - MegaLearning')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-3 mb-3">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h1 class="text-3xl font-bold text-gray-900">Kết quả tìm kiếm</h1>
        </div>
        <p class="text-gray-600">
            Tìm thấy <strong class="text-indigo-600">{{ $total }}</strong> kết quả cho 
            "<strong class="text-gray-900">{{ $query }}</strong>"
        </p>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('search.index', ['q' => $query, 'type' => 'all']) }}" 
               class="border-b-2 {{ $type === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-medium">
                Tất cả ({{ $total }})
            </a>
            @if(auth()->user()->hasRole('student'))
            <a href="{{ route('search.index', ['q' => $query, 'type' => 'courses']) }}" 
               class="border-b-2 {{ $type === 'courses' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-medium">
                Khóa học ({{ $courses->count() }})
            </a>
            @endif
            <a href="{{ route('search.index', ['q' => $query, 'type' => 'documents']) }}" 
               class="border-b-2 {{ $type === 'documents' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-medium">
                Tài liệu ({{ $documents->count() }})
            </a>
            <a href="{{ route('search.index', ['q' => $query, 'type' => 'forums']) }}" 
               class="border-b-2 {{ $type === 'forums' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} py-4 px-1 text-sm font-medium">
                Diễn đàn ({{ $forums->count() }})
            </a>
        </nav>
    </div>

    <!-- Search Results -->
    <div class="space-y-8">
        <!-- Courses Section -->
        @if(($type === 'all' || $type === 'courses') && $courses->count() > 0)
        <section>
            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                <span class="text-3xl mr-3">📚</span>
                Khóa học
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($courses as $course)
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <div class="h-32 bg-gradient-to-br from-blue-500 to-indigo-600 relative">
                        <div class="absolute inset-0 bg-black bg-opacity-20 group-hover:bg-opacity-30 transition-all"></div>
                        <div class="absolute bottom-3 left-3 right-3">
                            <h3 class="text-white font-bold text-lg line-clamp-2">{{ $course->name }}</h3>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center space-x-2 text-sm text-gray-600 mb-3">
                            <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold">
                                {{ $course->subject->name ?? 'N/A' }}
                            </span>
                            <span class="text-gray-400">•</span>
                            <span>{{ $course->teacher->name ?? 'N/A' }}</span>
                        </div>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            {{ $course->description ?? 'Không có mô tả' }}
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">
                                {{ $course->active_students_count }}/{{ $course->max_students }} học viên
                            </span>
                            <a href="{{ route('student.courses.browse', ['search' => $course->name]) }}" 
                               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Documents Section -->
        @if(($type === 'all' || $type === 'documents') && $documents->count() > 0)
        <section>
            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                <span class="text-3xl mr-3">📄</span>
                Tài liệu
            </h2>
            <div class="space-y-4">
                @foreach($documents as $document)
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 p-5">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $document->title }}</h3>
                            <div class="flex items-center space-x-3 text-sm text-gray-600 mb-2">
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">
                                    {{ $document->subject->name ?? 'N/A' }}
                                </span>
                                <span class="text-gray-400">•</span>
                                <span>{{ $document->uploadedBy->name ?? 'Unknown' }}</span>
                                <span class="text-gray-400">•</span>
                                <span>{{ $document->created_at->diffForHumans() }}</span>
                            </div>
                            @if($document->description)
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $document->description }}</p>
                            @endif
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('student.documents.download', $document->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-semibold transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Tải xuống
                                </a>
                                <span class="text-sm text-gray-500">
                                    {{ strtoupper(pathinfo($document->original_name, PATHINFO_EXTENSION)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Forums Section -->
        @if(($type === 'all' || $type === 'forums') && $forums->count() > 0)
        <section>
            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                <span class="text-3xl mr-3">💬</span>
                Diễn đàn thảo luận
            </h2>
            <div class="space-y-4">
                @foreach($forums as $forum)
                <a href="{{ route('forum.show', $forum->forum_question_id) }}" 
                   class="block bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 p-5">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $forum->title }}</h3>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit(strip_tags($forum->content), 200) }}</p>
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $forum->user->name }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    {{ $forum->answers_count }} câu trả lời
                                </span>
                                <span>{{ $forum->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Subjects Section (Sidebar suggestion) -->
        @if($type === 'all' && $subjects->count() > 0)
        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <span class="text-2xl mr-3">🎓</span>
                Môn học liên quan
            </h2>
            <div class="flex flex-wrap gap-3">
                @foreach($subjects as $subject)
                <a href="{{ auth()->user()->hasRole('student') ? route('student.courses.browse', ['subject_id' => $subject->id]) : '#' }}" 
                   class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white rounded-full font-semibold text-sm shadow-md hover:shadow-lg transition-all">
                    {{ $subject->name }}
                </a>
                @endforeach
            </div>
        </section>
        @endif

        <!-- No Results -->
        @if($total === 0)
        <div class="text-center py-16">
            <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Không tìm thấy kết quả</h3>
            <p class="text-gray-600 mb-6">
                Không có kết quả nào phù hợp với "<strong>{{ $query }}</strong>"
            </p>
            <a href="{{ url()->previous() }}" 
               class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Quay lại
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
