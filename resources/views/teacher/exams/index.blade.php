@extends('layouts.app')

@section('title', 'Quản lý Đề thi')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Inter', sans-serif; }
    .exam-card-accent-quiz { border-left: 4px solid #6366f1; }
    .exam-card-accent-midterm { border-left: 4px solid #f59e0b; }
    .exam-card-accent-final { border-left: 4px solid #8b5cf6; }
    .exam-card-accent-practice { border-left: 4px solid #10b981; }
    .dropdown-menu { display: none; }
    .dropdown:hover .dropdown-menu { display: block; }
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .toast-notification { animation: slideIn 0.3s ease-out; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Quản lý Đề thi</h1>
            <p class="text-gray-600 mt-2">Tạo và quản lý các đề thi cho học sinh</p>
        </div>
        <a href="{{ route('teacher.exams.create') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold px-6 py-3 rounded-xl shadow-md transition-all duration-200 transform hover:scale-105 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Tạo Đề thi Mới</span>
        </a>
    </div>

    <!-- Toast Notifications -->
    @if(session('success'))
    <div class="fixed top-6 right-6 z-50 toast-notification">
        <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-green-500 max-w-md">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <script>setTimeout(() => { document.querySelectorAll('.toast-notification').forEach(el => el.remove()); }, 5000);</script>
    @endif

    @if(session('error'))
    <div class="fixed top-6 right-6 z-50 toast-notification">
        <div class="bg-white rounded-xl shadow-lg p-4 border-l-4 border-red-500 max-w-md">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <script>setTimeout(() => { document.querySelectorAll('.toast-notification').forEach(el => el.remove()); }, 5000);</script>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('teacher.exams.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên đề thi..." class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all text-sm">
                </div>

                <!-- Subject Filter -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Môn học</label>
                    <select name="subject_id" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all text-sm">
                        <option value="">Tất cả môn học</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Loại đề thi</label>
                    <select name="type" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all text-sm">
                        <option value="">Tất cả loại</option>
                        <option value="quiz" {{ request('type') == 'quiz' ? 'selected' : '' }}>Kiểm tra</option>
                        <option value="midterm" {{ request('type') == 'midterm' ? 'selected' : '' }}>Giữa kỳ</option>
                        <option value="final" {{ request('type') == 'final' ? 'selected' : '' }}>Cuối kỳ</option>
                        <option value="practice" {{ request('type') == 'practice' ? 'selected' : '' }}>Luyện tập</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Trạng thái</label>
                    <select name="status" class="w-full px-4 py-2.5 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all text-sm">
                        <option value="">Tất cả</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã phát hành</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Đã lưu trữ</option>
                    </select>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex justify-between items-center pt-2">
                <div class="flex items-center space-x-3">
                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <span>Lọc</span>
                    </button>
                    <a href="{{ route('teacher.exams.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Exams List -->
    @if($exams->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($exams as $exam)
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden {{ 'exam-card-accent-' . $exam->type }}">
            <!-- Card Header (Compact) -->
            <div class="p-6">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $exam->title }}</h3>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $exam->type === 'quiz' ? 'bg-indigo-50 text-indigo-700' : 
                                   ($exam->type === 'midterm' ? 'bg-amber-50 text-amber-700' : 
                                   ($exam->type === 'final' ? 'bg-purple-50 text-purple-700' : 'bg-emerald-50 text-emerald-700')) }}">
                                @if($exam->type === 'quiz') Kiểm tra
                                @elseif($exam->type === 'midterm') Giữa kỳ
                                @elseif($exam->type === 'final') Cuối kỳ
                                @else Luyện tập
                                @endif
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $exam->status === 'published' ? 'bg-green-50 text-green-700' : 
                                   ($exam->status === 'draft' ? 'bg-gray-100 text-gray-600' : 'bg-orange-50 text-orange-700') }}">
                                @if($exam->status === 'published') Đã phát hành
                                @elseif($exam->status === 'draft') Nháp
                                @else Đã lưu trữ
                                @endif
                            </span>
                        </div>
                    </div>
                    <!-- Three-dot Menu -->
                    <div class="relative dropdown ml-2">
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                            </svg>
                        </button>
                        <div class="dropdown-menu absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10">
                            <a href="{{ route('teacher.exams.edit', $exam) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Chỉnh sửa
                            </a>
                            <form action="{{ route('teacher.exams.destroy', $exam) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa đề thi này?')" class="block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Subject & Class -->
                <div class="mb-4 space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span class="font-medium text-gray-700">{{ $exam->subject ? $exam->subject->name : 'N/A' }}</span>
                    </div>
                    @if($exam->classRoom)
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="font-medium text-gray-700">{{ $exam->classRoom->name }}</span>
                    </div>
                    @endif
                </div>

                <!-- Stats (Inline with Icons) -->
                <div class="mb-4 flex items-center space-x-4 text-sm text-gray-500">
                    <div class="flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold text-gray-900">{{ $exam->questions->count() }}</span>
                        <span>câu hỏi</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        <span class="font-semibold text-gray-900">{{ $exam->total_points ?? 0 }}</span>
                        <span>điểm</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold text-gray-900">{{ $exam->duration }}</span>
                        <span>phút</span>
                    </div>
                </div>

                <!-- Time -->
                @if($exam->start_time)
                <div class="mb-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-xs text-gray-500 mb-1">
                        <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-medium text-gray-600">Bắt đầu:</span>
                        <span class="ml-1">{{ $exam->start_time->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($exam->end_time)
                    <div class="flex items-center text-xs text-gray-500">
                        <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium text-gray-600">Kết thúc:</span>
                        <span class="ml-1">{{ $exam->end_time->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Primary Action Button (Outline Style) -->
                <a href="{{ route('teacher.exams.show', $exam) }}" class="block w-full px-4 py-2.5 bg-indigo-50 text-indigo-600 font-medium text-center rounded-lg hover:bg-indigo-100 transition-colors text-sm">
                    Xem chi tiết
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $exams->links() }}
    </div>

    @else
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-sm p-16 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl mb-6">
            <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Chưa có đề thi nào</h3>
        <p class="text-gray-500 mb-8 max-w-md mx-auto">Bắt đầu tạo đề thi đầu tiên cho học sinh của bạn</p>
        <a href="{{ route('teacher.exams.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-all transform hover:scale-105 shadow-sm hover:shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tạo Đề thi Đầu tiên
        </a>
    </div>
    @endif
</div>
@endsection
