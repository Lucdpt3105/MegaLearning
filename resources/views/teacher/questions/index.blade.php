@extends('layouts.app')

@section('title', 'Ngân hàng Câu hỏi')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Ngân hàng Câu hỏi</h1>
            <p class="text-gray-600 mt-2">Quản lý câu hỏi trắc nghiệm, tự luận của bạn</p>
        </div>
        <a href="{{ route('teacher.questions.create') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold px-6 py-3 rounded-xl shadow-md transition-all duration-200 transform hover:scale-105 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Thêm Câu hỏi Mới</span>
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <p class="text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('teacher.questions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nội dung câu hỏi..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <!-- Subject Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Môn học</label>
                <select name="subject_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Tất cả môn học</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Difficulty Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Độ khó</label>
                <select name="difficulty" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Tất cả</option>
                    <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Dễ</option>
                    <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Trung bình</option>
                    <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Khó</option>
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Loại câu hỏi</label>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Tất cả</option>
                    <option value="multiple_choice" {{ request('type') == 'multiple_choice' ? 'selected' : '' }}>Trắc nghiệm</option>
                    <option value="true_false" {{ request('type') == 'true_false' ? 'selected' : '' }}>Đúng/Sai</option>
                    <option value="essay" {{ request('type') == 'essay' ? 'selected' : '' }}>Tự luận</option>
                    <option value="fill_blank" {{ request('type') == 'fill_blank' ? 'selected' : '' }}>Điền khuyết</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="md:col-span-4 flex justify-end space-x-3">
                <a href="{{ route('teacher.questions.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Reset
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Lọc
                </button>
            </div>
        </form>
    </div>

    <!-- Questions List -->
    @if($questions->count() > 0)
    <div class="space-y-4">
        @foreach($questions as $question)
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <!-- Question Content -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <!-- Type Badge -->
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $question->type === 'multiple_choice' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $question->type === 'true_false' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $question->type === 'essay' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $question->type === 'fill_blank' ? 'bg-orange-100 text-orange-800' : '' }}">
                                @if($question->type === 'multiple_choice') Trắc nghiệm
                                @elseif($question->type === 'true_false') Đúng/Sai
                                @elseif($question->type === 'essay') Tự luận
                                @else Điền khuyết
                                @endif
                            </span>

                            <!-- Difficulty Badge -->
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $question->difficulty === 'easy' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $question->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $question->difficulty === 'hard' ? 'bg-red-100 text-red-800' : '' }}">
                                @if($question->difficulty === 'easy') Dễ
                                @elseif($question->difficulty === 'medium') Trung bình
                                @else Khó
                                @endif
                            </span>

                            <!-- Points -->
                            <span class="px-3 py-1 text-xs font-semibold bg-indigo-100 text-indigo-800 rounded-full">
                                {{ $question->points }} điểm
                            </span>
                        </div>

                        <!-- Content -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            {!! Str::limit($question->content, 200) !!}
                        </h3>

                        <!-- Subject & Creator -->
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                {{ $question->subject->name }}
                            </span>
                            <span>•</span>
                            <span>{{ $question->created_at->diffForHumans() }}</span>
                        </div>

                        <!-- Answers Preview (if multiple choice) -->
                        @if(in_array($question->type, ['multiple_choice', 'true_false']) && $question->answers->count() > 0)
                        <div class="mt-3 space-y-1">
                            @foreach($question->answers->take(2) as $answer)
                            <div class="flex items-center text-sm">
                                <span class="w-2 h-2 rounded-full {{ $answer->is_correct ? 'bg-green-500' : 'bg-gray-300' }} mr-2"></span>
                                <span class="{{ $answer->is_correct ? 'text-green-700 font-medium' : 'text-gray-600' }}">
                                    {{ Str::limit($answer->content, 80) }}
                                </span>
                            </div>
                            @endforeach
                            @if($question->answers->count() > 2)
                            <p class="text-xs text-gray-500 ml-4">+ {{ $question->answers->count() - 2 }} đáp án khác</p>
                            @endif
                        </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col space-y-2 ml-4">
                        <a href="{{ route('teacher.questions.show', $question) }}" class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors text-center text-sm font-medium">
                            Chi tiết
                        </a>
                        <a href="{{ route('teacher.questions.edit', $question) }}" class="px-4 py-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition-colors text-center text-sm font-medium">
                            Sửa
                        </a>
                        <form action="{{ route('teacher.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa câu hỏi này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors text-sm font-medium">
                                Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $questions->links() }}
    </div>
    @else
    <div class="bg-white rounded-xl shadow-md p-12 text-center">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có câu hỏi nào</h3>
        <p class="text-gray-600 mb-6">Bắt đầu tạo câu hỏi cho ngân hàng của bạn</p>
        <a href="{{ route('teacher.questions.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tạo Câu hỏi Đầu tiên
        </a>
    </div>
    @endif
</div>
@endsection
