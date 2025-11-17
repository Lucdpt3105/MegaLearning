@extends('layouts.app')

@section('title', 'Câu hỏi - ' . $subject->name)

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('teacher.questions.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $subject->name }}</h1>
                <p class="text-gray-600 mt-1">Ngân hàng câu hỏi môn {{ $subject->name }}</p>
            </div>
        </div>
        <a href="{{ route('teacher.questions.create', ['subject' => $subject->id]) }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold px-6 py-3 rounded-xl shadow-md transition-all duration-200 transform hover:scale-105 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Thêm Câu hỏi Mới</span>
        </a>
    </div>

    <!-- Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <p class="text-green-700 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <p class="text-red-700 font-medium">{{ session('error') }}</p>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <p class="text-red-700 font-medium">Có lỗi xảy ra:</p>
        <ul class="list-disc list-inside mt-2">
            @foreach($errors->all() as $error)
                <li class="text-red-600">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Advanced Filter Section -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('teacher.questions.by-subject', $subject) }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nội dung câu hỏi..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <!-- Topic/Chapter Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chương/Bài</label>
                    <select name="topic_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Tất cả chương</option>
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                                {{ $topic->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Bloom Level Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mức độ</label>
                    <select name="bloom_level" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Tất cả mức độ</option>
                        <option value="remember" {{ request('bloom_level') == 'remember' ? 'selected' : '' }}>🟢 Nhận biết</option>
                        <option value="understand" {{ request('bloom_level') == 'understand' ? 'selected' : '' }}>🔵 Thông hiểu</option>
                        <option value="apply" {{ request('bloom_level') == 'apply' ? 'selected' : '' }}>🟡 Vận dụng</option>
                        <option value="analyze" {{ request('bloom_level') == 'analyze' ? 'selected' : '' }}>🔴 VD Cao</option>
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
            </div>

            <!-- Filter Buttons -->
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        🔍 Lọc
                    </button>
                    <a href="{{ route('teacher.questions.by-subject', $subject) }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Reset
                    </a>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('teacher.questions.export', $subject) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        📥 Export Excel
                    </a>
                    <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        📤 Import Excel
                    </button>
                </div>
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
                        <div class="flex items-center space-x-2 mb-3">
                            <!-- Bloom Level Badge -->
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $question->bloom_level === 'remember' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $question->bloom_level === 'understand' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $question->bloom_level === 'apply' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $question->bloom_level === 'analyze' ? 'bg-red-100 text-red-800' : '' }}">
                                @if($question->bloom_level === 'remember') 🟢 Nhận biết
                                @elseif($question->bloom_level === 'understand') 🔵 Thông hiểu
                                @elseif($question->bloom_level === 'apply') 🟡 Vận dụng
                                @else 🔴 VD Cao
                                @endif
                            </span>

                            <!-- Type Badge -->
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $question->type === 'multiple_choice' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $question->type === 'true_false' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $question->type === 'essay' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $question->type === 'fill_blank' ? 'bg-orange-100 text-orange-800' : '' }}">
                                @if($question->type === 'multiple_choice') 
                                    Trắc nghiệm {{ $question->correct_answer_count > 1 ? '(Nhiều đáp án)' : '' }}
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
                                @elseif($question->difficulty === 'medium') TB
                                @else Khó
                                @endif
                            </span>

                            <!-- Points -->
                            <span class="px-3 py-1 text-xs font-semibold bg-indigo-100 text-indigo-800 rounded-full">
                                {{ $question->points }} điểm
                            </span>

                            <!-- Usage Count -->
                            @if($question->usage_count > 0)
                            <span class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">
                                📊 Đã dùng {{ $question->usage_count }} lần
                            </span>
                            @endif
                        </div>

                        <!-- Content -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            {!! Str::limit($question->content, 200) !!}
                        </h3>

                        <!-- Topic & Created -->
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            @if($question->topic)
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                {{ $question->topic->name }}
                            </span>
                            <span>•</span>
                            @endif
                            <span>{{ $question->created_at->diffForHumans() }}</span>
                        </div>

                        <!-- Tags -->
                        @if($question->tags && count($question->tags) > 0)
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($question->tags as $tag)
                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">
                                🏷️ {{ $tag }}
                            </span>
                            @endforeach
                        </div>
                        @endif

                        <!-- Media Icons -->
                        <div class="mt-3 flex items-center space-x-3">
                            @if($question->image_url)
                            <span class="text-xs text-gray-500">🖼️ Có hình ảnh</span>
                            @endif
                            @if($question->audio_url)
                            <span class="text-xs text-gray-500">🎵 Có audio</span>
                            @endif
                            @if($question->video_url)
                            <span class="text-xs text-gray-500">🎬 Có video</span>
                            @endif
                        </div>
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
        <p class="text-gray-600 mb-6">Bắt đầu tạo câu hỏi cho môn {{ $subject->name }}</p>
        <a href="{{ route('teacher.questions.create', ['subject' => $subject->id]) }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tạo Câu hỏi Đầu tiên
        </a>
    </div>
    @endif
</div>

<!-- Import Modal -->
<div id="importModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Import Câu hỏi từ Excel</h3>
            <form action="{{ route('teacher.questions.import', $subject) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chọn file Excel</label>
                    <input type="file" name="file" accept=".xlsx,.xls" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <p class="text-xs text-gray-500 mt-2">
                        <a href="{{ route('teacher.questions.download-template') }}" class="text-indigo-600 hover:underline">
                            📥 Tải file mẫu
                        </a>
                    </p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Hủy
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
