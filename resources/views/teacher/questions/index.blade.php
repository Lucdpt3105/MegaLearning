@extends('layouts.app')

@section('title', 'Ngân hàng Câu hỏi')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    
    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }
    
    @keyframes slide-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }
    
    .difficulty-accent-easy {
        border-left: 4px solid #10b981;
    }
    .difficulty-accent-medium {
        border-left: 4px solid #f59e0b;
    }
    .difficulty-accent-hard {
        border-left: 4px solid #ef4444;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-50" style="font-family: 'Inter', system-ui, -apple-system, sans-serif;">
    <!-- Hero Header -->
    <div class="bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Ngân hàng Câu hỏi</h1>
                        <p class="text-sm text-slate-600 mt-1">Quản lý và tổ chức câu hỏi của bạn một cách thông minh</p>
                    </div>
                </div>
                <a href="{{ route('teacher.questions.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span>Tạo câu hỏi mới</span>
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">

    <!-- Toast Notifications -->
    @if(session('success'))
    <div id="toast-success" class="fixed top-4 right-4 z-50 flex items-center w-full max-w-sm p-4 mb-4 text-gray-900 bg-white rounded-xl shadow-lg border border-gray-200 animate-slide-in">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-green-500 bg-green-50 rounded-lg">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3 text-sm font-medium">{{ session('success') }}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8" onclick="this.parentElement.remove()">
            <span class="sr-only">Close</span>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    <script>
        setTimeout(() => { 
            const toast = document.getElementById('toast-success');
            if(toast) toast.remove();
        }, 5000);
    </script>
    @endif

    @if(session('error'))
    <div id="toast-error" class="fixed top-4 right-4 z-50 flex items-center w-full max-w-sm p-4 mb-4 text-gray-900 bg-white rounded-xl shadow-lg border border-gray-200 animate-slide-in">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-red-500 bg-red-50 rounded-lg">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3 text-sm font-medium">{{ session('error') }}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8" onclick="this.parentElement.remove()">
            <span class="sr-only">Close</span>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    <script>
        setTimeout(() => { 
            const toast = document.getElementById('toast-error');
            if(toast) toast.remove();
        }, 5000);
    </script>
    @endif

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-slate-900">Bộ lọc</h2>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-50 border border-slate-200 rounded-lg hover:bg-slate-100 transition-colors">
                    <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export
                </button>
                <button type="button" class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-50 border border-slate-200 rounded-lg hover:bg-slate-100 transition-colors">
                    <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Import
                </button>
            </div>
        </div>
        
        <form method="GET" action="{{ route('teacher.questions.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide">Tìm kiếm</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Nội dung câu hỏi..." 
                               class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border-0 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                </div>

                <!-- Subject Filter -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide">Môn học</label>
                    <select name="subject_id" 
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border-0 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="">Tất cả môn học</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Difficulty Filter -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide">Độ khó</label>
                    <select name="difficulty" 
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border-0 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="">Tất cả</option>
                        <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Dễ</option>
                        <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Trung bình</option>
                        <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Khó</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide">Loại câu hỏi</label>
                    <select name="type" 
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border-0 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="">Tất cả</option>
                        <option value="multiple_choice" {{ request('type') == 'multiple_choice' ? 'selected' : '' }}>Trắc nghiệm</option>
                        <option value="true_false" {{ request('type') == 'true_false' ? 'selected' : '' }}>Đúng/Sai</option>
                        <option value="essay" {{ request('type') == 'essay' ? 'selected' : '' }}>Tự luận</option>
                        <option value="fill_blank" {{ request('type') == 'fill_blank' ? 'selected' : '' }}>Điền khuyết</option>
                    </select>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex justify-end space-x-3 pt-2">
                <a href="{{ route('teacher.questions.index') }}" 
                   class="px-5 py-2.5 text-sm font-medium text-slate-700 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                    Đặt lại
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 text-sm font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                    Áp dụng bộ lọc
                </button>
            </div>
        </form>
    </div>

    <!-- Questions List -->
    @if($questions->count() > 0)
    <div class="space-y-4 mb-8">
        @foreach($questions as $index => $question)
        <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden
            {{ $question->difficulty === 'easy' ? 'difficulty-accent-easy' : '' }}
            {{ $question->difficulty === 'medium' ? 'difficulty-accent-medium' : '' }}
            {{ $question->difficulty === 'hard' ? 'difficulty-accent-hard' : '' }}">
            <div class="p-6">
                <!-- Question Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1 pr-4">
                        <h3 class="text-lg font-semibold text-slate-900 mb-3 leading-snug">
                            {!! Str::limit(strip_tags($question->content), 150) !!}
                        </h3>
                        
                        <!-- Tags -->
                        <div class="flex flex-wrap items-center gap-2">
                            <!-- Type Badge -->
                            <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full
                                {{ $question->type === 'multiple_choice' ? 'bg-blue-50 text-blue-700' : '' }}
                                {{ $question->type === 'true_false' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                {{ $question->type === 'essay' ? 'bg-purple-50 text-purple-700' : '' }}
                                {{ $question->type === 'fill_blank' ? 'bg-amber-50 text-amber-700' : '' }}">
                                @if($question->type === 'multiple_choice') Trắc nghiệm
                                @elseif($question->type === 'true_false') Đúng/Sai
                                @elseif($question->type === 'essay') Tự luận
                                @else Điền khuyết
                                @endif
                            </span>

                            <!-- Difficulty Badge -->
                            <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full
                                {{ $question->difficulty === 'easy' ? 'bg-green-50 text-green-700' : '' }}
                                {{ $question->difficulty === 'medium' ? 'bg-orange-50 text-orange-700' : '' }}
                                {{ $question->difficulty === 'hard' ? 'bg-red-50 text-red-700' : '' }}">
                                @if($question->difficulty === 'easy') Dễ
                                @elseif($question->difficulty === 'medium') Trung bình
                                @else Khó
                                @endif
                            </span>

                            <!-- Subject Badge -->
                            <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold bg-indigo-50 text-indigo-700 rounded-full">
                                {{ $question->subject->name }}
                            </span>

                            <!-- Points Badge -->
                            <span class="inline-flex items-center px-3 py-1.5 text-xs font-bold bg-slate-100 text-slate-700 rounded-full">
                                {{ $question->points }} điểm
                            </span>
                        </div>
                    </div>

                    <!-- Action Icons -->
                    <div class="flex items-center space-x-1 flex-shrink-0">
                        <a href="{{ route('teacher.questions.show', $question) }}" 
                           class="group p-2.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200" 
                           title="Xem chi tiết">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        <a href="{{ route('teacher.questions.edit', $question) }}" 
                           class="group p-2.5 text-orange-600 hover:bg-orange-50 rounded-lg transition-all duration-200" 
                           title="Chỉnh sửa">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <form action="{{ route('teacher.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa câu hỏi này?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="group p-2.5 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200" 
                                    title="Xóa">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Answers Preview -->
                @if(in_array($question->type, ['multiple_choice', 'true_false']) && $question->answers->count() > 0)
                <div class="space-y-2 mb-4">
                    @foreach($question->answers->take(2) as $answer)
                    <div class="flex items-center text-sm px-3.5 py-2.5 rounded-lg {{ $answer->is_correct ? 'bg-emerald-50 border border-emerald-100' : 'bg-slate-50' }}">
                        <div class="w-2 h-2 rounded-full {{ $answer->is_correct ? 'bg-emerald-500' : 'bg-slate-400' }} mr-3 flex-shrink-0"></div>
                        <span class="{{ $answer->is_correct ? 'text-emerald-700 font-semibold' : 'text-slate-600' }}">
                            {{ Str::limit($answer->content, 100) }}
                        </span>
                    </div>
                    @endforeach
                    @if($question->answers->count() > 2)
                    <p class="text-xs text-slate-500 ml-5 mt-2">+ {{ $question->answers->count() - 2 }} đáp án khác</p>
                    @endif
                </div>
                @endif

                <!-- Footer with timestamp -->
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <div class="flex items-center text-xs text-slate-500">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $question->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $questions->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-16 text-center">
        <div class="w-24 h-24 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-slate-900 mb-3">Chưa có câu hỏi nào</h3>
        <p class="text-slate-600 mb-8 max-w-md mx-auto text-base">
            Bắt đầu xây dựng ngân hàng câu hỏi của bạn. Tạo câu hỏi đầu tiên ngay bây giờ!
        </p>
        <a href="{{ route('teacher.questions.create') }}" 
           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-xl hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 shadow-md hover:shadow-lg font-semibold transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tạo câu hỏi đầu tiên
        </a>
    </div>
    @endif
</div>
</div>
@endsection
