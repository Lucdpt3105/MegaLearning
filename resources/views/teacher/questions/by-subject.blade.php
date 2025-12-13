@extends('layouts.app')

@section('title', 'Câu hỏi - ' . $subject->name)

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    
    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
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
    <!-- Header -->
    <div class="bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('teacher.questions.index') }}" class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">{{ $subject->name }}</h1>
                        <p class="text-sm text-slate-600 mt-1">Ngân hàng câu hỏi môn {{ $subject->name }}</p>
                    </div>
                </div>
                <a href="{{ route('teacher.questions.create', ['subject' => $subject->id]) }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span>Thêm Câu hỏi Mới</span>
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">

    <!-- Toast Messages -->
    @if(session('success'))
    <div id="toast-success" class="fixed top-4 right-4 z-50 flex items-center w-full max-w-sm p-4 mb-4 text-slate-900 bg-white rounded-xl shadow-lg border border-slate-200">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-green-500 bg-green-50 rounded-lg">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3 text-sm font-medium">{{ session('success') }}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-slate-400 hover:text-slate-900 rounded-lg p-1.5 hover:bg-slate-100 inline-flex h-8 w-8" onclick="this.parentElement.remove()">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    <script>setTimeout(() => { const toast = document.getElementById('toast-success'); if(toast) toast.remove(); }, 5000);</script>
    @endif

    @if(session('error'))
    <div id="toast-error" class="fixed top-4 right-4 z-50 flex items-center w-full max-w-sm p-4 mb-4 text-slate-900 bg-white rounded-xl shadow-lg border border-slate-200">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-red-500 bg-red-50 rounded-lg">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3 text-sm font-medium">{{ session('error') }}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-slate-400 hover:text-slate-900 rounded-lg p-1.5 hover:bg-slate-100 inline-flex h-8 w-8" onclick="this.parentElement.remove()">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    <script>setTimeout(() => { const toast = document.getElementById('toast-error'); if(toast) toast.remove(); }, 5000);</script>
    @endif

    @if($errors->any())
    <div id="toast-errors" class="fixed top-4 right-4 z-50 w-full max-w-sm p-4 mb-4 text-slate-900 bg-white rounded-xl shadow-lg border border-red-200">
        <div class="flex items-start">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-10 h-10 text-red-500 bg-red-50 rounded-lg">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <div class="text-sm font-semibold text-red-800 mb-1">Có lỗi xảy ra</div>
                @foreach($errors->all() as $error)
                    <div class="text-xs text-red-600">• {{ $error }}</div>
                @endforeach
            </div>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-slate-400 hover:text-slate-900 rounded-lg p-1.5 hover:bg-slate-100 inline-flex h-8 w-8" onclick="this.parentElement.parentElement.remove()">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>
    <script>setTimeout(() => { const toast = document.getElementById('toast-errors'); if(toast) toast.remove(); }, 7000);</script>
    @endif

    <!-- Advanced Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <form method="GET" action="{{ route('teacher.questions.by-subject', $subject) }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nội dung câu hỏi..." 
                           class="w-full px-4 py-2.5 text-sm bg-slate-50 border-0 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                <!-- Topic/Chapter Filter -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide">Chương/Bài</label>
                    <select name="topic_id" class="w-full px-4 py-2.5 text-sm bg-slate-50 border-0 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="">Tất cả chương</option>
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                                {{ $topic->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Bloom Level Filter -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide">Mức độ</label>
                    <select name="bloom_level" class="w-full px-4 py-2.5 text-sm bg-slate-50 border-0 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="">Tất cả mức độ</option>
                        <option value="remember" {{ request('bloom_level') == 'remember' ? 'selected' : '' }}>🟢 Nhận biết</option>
                        <option value="understand" {{ request('bloom_level') == 'understand' ? 'selected' : '' }}>🔵 Thông hiểu</option>
                        <option value="apply" {{ request('bloom_level') == 'apply' ? 'selected' : '' }}>🟡 Vận dụng</option>
                        <option value="analyze" {{ request('bloom_level') == 'analyze' ? 'selected' : '' }}>🔴 VD Cao</option>
                    </select>
                </div>

                <!-- Difficulty Filter -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide">Độ khó</label>
                    <select name="difficulty" class="w-full px-4 py-2.5 text-sm bg-slate-50 border-0 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="">Tất cả</option>
                        <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Dễ</option>
                        <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Trung bình</option>
                        <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Khó</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide">Loại câu hỏi</label>
                    <select name="type" class="w-full px-4 py-2.5 text-sm bg-slate-50 border-0 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        <option value="">Tất cả</option>
                        <option value="multiple_choice" {{ request('type') == 'multiple_choice' ? 'selected' : '' }}>Trắc nghiệm</option>
                        <option value="true_false" {{ request('type') == 'true_false' ? 'selected' : '' }}>Đúng/Sai</option>
                        <option value="essay" {{ request('type') == 'essay' ? 'selected' : '' }}>Tự luận</option>
                        <option value="fill_blank" {{ request('type') == 'fill_blank' ? 'selected' : '' }}>Điền khuyết</option>
                    </select>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex justify-between items-center pt-2">
                <div class="flex items-center space-x-3">
                    <button type="submit" class="px-6 py-2.5 text-sm font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                        🔍 Lọc
                    </button>
                    <a href="{{ route('teacher.questions.by-subject', $subject) }}" class="px-5 py-2.5 text-sm font-medium text-slate-700 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                        Reset
                    </a>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('teacher.questions.export', $subject) }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-50 border border-slate-200 rounded-lg hover:bg-slate-100 transition-colors">
                        <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export
                    </a>
                    <button type="button" onclick="document.getElementById('importModal').classList.remove('hidden')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-50 border border-slate-200 rounded-lg hover:bg-slate-100 transition-colors">
                        <svg class="w-4 h-4 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Import
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Questions List -->
    @if($questions->count() > 0)
    <div class="space-y-4">
        @foreach($questions as $question)
        <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden
            {{ $question->difficulty === 'easy' ? 'difficulty-accent-easy' : '' }}
            {{ $question->difficulty === 'medium' ? 'difficulty-accent-medium' : '' }}
            {{ $question->difficulty === 'hard' ? 'difficulty-accent-hard' : '' }}">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <!-- Question Content -->
                    <div class="flex-1 pr-4">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <!-- Bloom Level Badge -->
                            <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full
                                {{ $question->bloom_level === 'remember' ? 'bg-green-50 text-green-700' : '' }}
                                {{ $question->bloom_level === 'understand' ? 'bg-blue-50 text-blue-700' : '' }}
                                {{ $question->bloom_level === 'apply' ? 'bg-yellow-50 text-yellow-700' : '' }}
                                {{ $question->bloom_level === 'analyze' ? 'bg-red-50 text-red-700' : '' }}">
                                @if($question->bloom_level === 'remember') Nhận biết
                                @elseif($question->bloom_level === 'understand') Thông hiểu
                                @elseif($question->bloom_level === 'apply') Vận dụng
                                @else VD Cao
                                @endif
                            </span>

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

                            <!-- Points -->
                            <span class="inline-flex items-center px-3 py-1.5 text-xs font-bold bg-slate-100 text-slate-700 rounded-full">
                                {{ $question->points }} điểm
                            </span>

                            <!-- Usage Count -->
                            @if($question->usage_count > 0)
                            <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold bg-indigo-50 text-indigo-700 rounded-full">
                                📊 {{ $question->usage_count }}
                            </span>
                            @endif
                        </div>

                        <!-- Content -->
                        <h3 class="text-lg font-semibold text-slate-900 mb-3 leading-snug">
                            {!! Str::limit($question->content, 200) !!}
                        </h3>

                        <!-- Topic & Created -->
                        <div class="flex items-center space-x-3 text-xs text-slate-500 mb-2">
                            @if($question->topic)
                            <span class="flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                {{ $question->topic->name }}
                            </span>
                            <span>•</span>
                            @endif
                            <span class="flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $question->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <!-- Tags -->
                        @if($question->tags && count($question->tags) > 0)
                        <div class="flex flex-wrap gap-1.5 mb-2">
                            @foreach($question->tags as $tag)
                            <span class="px-2 py-0.5 text-xs bg-slate-100 text-slate-600 rounded">
                                {{ $tag }}
                            </span>
                            @endforeach
                        </div>
                        @endif

                        <!-- Media Icons -->
                        @if($question->image_url || $question->audio_url || $question->video_url)
                        <div class="flex items-center space-x-2 text-xs text-slate-500">
                            @if($question->image_url)
                            <span>🖼️</span>
                            @endif
                            @if($question->audio_url)
                            <span>🎵</span>
                            @endif
                            @if($question->video_url)
                            <span>🎬</span>
                            @endif
                        </div>
                        @endif
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
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $questions->links() }}
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-16 text-center">
        <div class="w-24 h-24 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-slate-900 mb-3">Chưa có câu hỏi nào</h3>
        <p class="text-slate-600 mb-8 max-w-md mx-auto text-base">Bắt đầu tạo câu hỏi cho môn {{ $subject->name }}</p>
        <a href="{{ route('teacher.questions.create', ['subject' => $subject->id]) }}" 
           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-xl hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 shadow-md hover:shadow-lg font-semibold transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tạo Câu hỏi Đầu tiên
        </a>
    </div>
    @endif
</div>
</div>

<!-- Import Modal -->
<div id="importModal" class="hidden fixed inset-0 bg-slate-900 bg-opacity-50 overflow-y-auto h-full w-full z-50 backdrop-blur-sm">
    <div class="relative top-20 mx-auto p-6 border-0 w-full max-w-md shadow-2xl rounded-2xl bg-white">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900">Import Câu hỏi</h3>
            <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form action="{{ route('teacher.questions.import', $subject) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-3">Chọn file Excel</label>
                <input type="file" name="file" accept=".xlsx,.xls" required 
                       class="w-full px-4 py-3 bg-slate-50 border-0 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                <p class="text-xs text-slate-500 mt-3">
                    <a href="{{ route('teacher.questions.download-template') }}" class="text-indigo-600 hover:text-indigo-700 hover:underline font-medium">
                        📥 Tải file mẫu
                    </a>
                </p>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" 
                        class="px-5 py-2.5 text-sm font-medium text-slate-700 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                    Hủy
                </button>
                <button type="submit" 
                        class="px-6 py-2.5 text-sm font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                    Import
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
