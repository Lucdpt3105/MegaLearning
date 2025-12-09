@extends('layouts.app')

@section('title', 'Ngân hàng Câu hỏi')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Hero Header with Background Image -->
    <div class="relative overflow-hidden bg-white shadow-sm mb-8">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1606326608606-aa0b62935f2b?w=1600&q=80" 
                 alt="Questions" 
                 class="w-full h-full object-cover opacity-10">
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-6 py-12">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                Ngân hàng Câu hỏi
                            </h1>
                            <p class="text-gray-600 mt-1">Quản lý và tổ chức câu hỏi của bạn một cách thông minh</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('teacher.questions.create') }}" 
                   class="group relative overflow-hidden bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold px-8 py-4 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center space-x-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span>Tạo câu hỏi mới</span>
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 pb-12">

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-5 rounded-2xl shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p class="ml-4 text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 p-5 rounded-2xl shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <p class="ml-4 text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-gray-100 p-8 mb-8">
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900">Bộ lọc tìm kiếm</h2>
        </div>
        
        <form method="GET" action="{{ route('teacher.questions.index') }}" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">Tìm kiếm</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Nội dung câu hỏi..." 
                               class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                    </div>
                </div>

                <!-- Subject Filter -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">Môn học</label>
                    <div class="relative">
                        <select name="subject_id" 
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent appearance-none bg-white transition-all duration-200">
                            <option value="">Tất cả môn học</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Difficulty Filter -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">Độ khó</label>
                    <div class="relative">
                        <select name="difficulty" 
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent appearance-none bg-white transition-all duration-200">
                            <option value="">Tất cả</option>
                            <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Dễ</option>
                            <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Trung bình</option>
                            <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Khó</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Type Filter -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">Loại câu hỏi</label>
                    <div class="relative">
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">Loại câu hỏi</label>
                    <div class="relative">
                        <select name="type" 
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent appearance-none bg-white transition-all duration-200">
                            <option value="">Tất cả</option>
                            <option value="multiple_choice" {{ request('type') == 'multiple_choice' ? 'selected' : '' }}>Trắc nghiệm</option>
                            <option value="true_false" {{ request('type') == 'true_false' ? 'selected' : '' }}>Đúng/Sai</option>
                            <option value="essay" {{ request('type') == 'essay' ? 'selected' : '' }}>Tự luận</option>
                            <option value="fill_blank" {{ request('type') == 'fill_blank' ? 'selected' : '' }}>Điền khuyết</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('teacher.questions.index') }}" 
                   class="px-6 py-3 border-2 border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 font-medium">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Đặt lại
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Áp dụng bộ lọc
                </button>
            </div>
        </form>
    </div>

    <!-- Questions Grid -->
    @if($questions->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        @foreach($questions as $index => $question)
        <div class="group bg-white rounded-3xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100">
            <!-- Card Header with Image -->
            <div class="relative h-32 overflow-hidden bg-gradient-to-br from-indigo-50 to-purple-50">
                @php
                    $images = [
                        'https://images.unsplash.com/photo-1516397281156-ca07cf9746fc?w=800&q=80',
                        'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&q=80',
                        'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&q=80',
                        'https://images.unsplash.com/photo-1488190211105-8b0e65b80b4e?w=800&q=80',
                        'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=800&q=80'
                    ];
                    $randomImage = $images[$index % count($images)];
                @endphp
                <img src="{{ $randomImage }}" alt="Question" class="w-full h-full object-cover opacity-30 group-hover:opacity-40 group-hover:scale-110 transition-all duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                
                <!-- Badges on Image -->
                <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                    <!-- Type Badge -->
                    <span class="px-3 py-1.5 text-xs font-bold rounded-full backdrop-blur-md shadow-lg
                        {{ $question->type === 'multiple_choice' ? 'bg-blue-500/90 text-white' : '' }}
                        {{ $question->type === 'true_false' ? 'bg-green-500/90 text-white' : '' }}
                        {{ $question->type === 'essay' ? 'bg-purple-500/90 text-white' : '' }}
                        {{ $question->type === 'fill_blank' ? 'bg-orange-500/90 text-white' : '' }}">
                        @if($question->type === 'multiple_choice') 📝 Trắc nghiệm
                        @elseif($question->type === 'true_false') ✓✗ Đúng/Sai
                        @elseif($question->type === 'essay') 📄 Tự luận
                        @else ✏️ Điền khuyết
                        @endif
                    </span>

                    <!-- Difficulty Badge -->
                    <span class="px-3 py-1.5 text-xs font-bold rounded-full backdrop-blur-md shadow-lg
                        {{ $question->difficulty === 'easy' ? 'bg-green-500/90 text-white' : '' }}
                        {{ $question->difficulty === 'medium' ? 'bg-yellow-500/90 text-white' : '' }}
                        {{ $question->difficulty === 'hard' ? 'bg-red-500/90 text-white' : '' }}">
                        @if($question->difficulty === 'easy') ⭐ Dễ
                        @elseif($question->difficulty === 'medium') ⭐⭐ Trung bình
                        @else ⭐⭐⭐ Khó
                        @endif
                    </span>
                </div>

                <!-- Points Badge -->
                <div class="absolute top-4 right-4">
                    <div class="bg-white/95 backdrop-blur-md px-4 py-2 rounded-full shadow-lg">
                        <span class="text-indigo-600 font-bold text-sm">{{ $question->points }} điểm</span>
                    </div>
                </div>
            </div>

            <!-- Card Content -->
            <div class="p-6">
                <!-- Question Content -->
                <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-indigo-600 transition-colors duration-200">
                    {!! Str::limit(strip_tags($question->content), 120) !!}
                </h3>

                <!-- Meta Info -->
                <div class="flex items-center space-x-3 text-sm text-gray-600 mb-4 pb-4 border-b border-gray-100">
                    <span class="flex items-center bg-indigo-50 px-3 py-1 rounded-lg">
                        <svg class="w-4 h-4 mr-1.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span class="font-medium text-indigo-600">{{ $question->subject->name }}</span>
                    </span>
                    <span class="flex items-center text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $question->created_at->diffForHumans() }}
                    </span>
                </div>

                <!-- Answers Preview -->
                @if(in_array($question->type, ['multiple_choice', 'true_false']) && $question->answers->count() > 0)
                <div class="space-y-2 mb-4">
                    @foreach($question->answers->take(2) as $answer)
                    <div class="flex items-center p-2 rounded-lg {{ $answer->is_correct ? 'bg-green-50' : 'bg-gray-50' }}">
                        <div class="w-2 h-2 rounded-full {{ $answer->is_correct ? 'bg-green-500' : 'bg-gray-300' }} mr-3"></div>
                        <span class="text-sm {{ $answer->is_correct ? 'text-green-700 font-semibold' : 'text-gray-600' }}">
                            {{ Str::limit($answer->content, 60) }}
                        </span>
                    </div>
                    @endforeach
                    @if($question->answers->count() > 2)
                    <p class="text-xs text-gray-500 ml-5">+ {{ $question->answers->count() - 2 }} đáp án khác...</p>
                    @endif
                </div>
                @endif

                <!-- Actions -->
                <div class="flex space-x-2">
                    <a href="{{ route('teacher.questions.show', $question) }}" 
                       class="flex-1 px-4 py-2.5 bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-600 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all duration-200 text-center text-sm font-semibold border border-blue-100">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Chi tiết
                    </a>
                    <a href="{{ route('teacher.questions.edit', $question) }}" 
                       class="flex-1 px-4 py-2.5 bg-gradient-to-r from-amber-50 to-yellow-50 text-amber-600 rounded-xl hover:from-amber-100 hover:to-yellow-100 transition-all duration-200 text-center text-sm font-semibold border border-amber-100">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Sửa
                    </a>
                    <form action="{{ route('teacher.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa câu hỏi này?')" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-4 py-2.5 bg-gradient-to-r from-red-50 to-rose-50 text-red-600 rounded-xl hover:from-red-100 hover:to-rose-100 transition-all duration-200 text-sm font-semibold border border-red-100">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Xóa
                        </button>
                    </form>
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
    <div class="relative bg-white rounded-3xl shadow-xl p-16 text-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1200&q=80" 
                 alt="Empty" 
                 class="w-full h-full object-cover opacity-5">
        </div>
        <div class="relative z-10">
            <div class="w-24 h-24 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <svg class="w-12 h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-3">Chưa có câu hỏi nào</h3>
            <p class="text-gray-600 mb-8 text-lg max-w-md mx-auto">
                Bắt đầu xây dựng ngân hàng câu hỏi của bạn. Tạo câu hỏi đầu tiên ngay bây giờ!
            </p>
            <a href="{{ route('teacher.questions.create') }}" 
               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105 font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tạo câu hỏi đầu tiên
            </a>
        </div>
    </div>
    @endif
</div>
</div>
@endsection
