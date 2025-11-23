@extends('layouts.app')

@section('title', 'Quản lý Đề thi')

@section('content')
<div class="p-6">
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

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('teacher.exams.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tên đề thi..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
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

                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Loại đề thi</label>
                    <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Tất cả loại</option>
                        <option value="quiz" {{ request('type') == 'quiz' ? 'selected' : '' }}>Kiểm tra</option>
                        <option value="midterm" {{ request('type') == 'midterm' ? 'selected' : '' }}>Giữa kỳ</option>
                        <option value="final" {{ request('type') == 'final' ? 'selected' : '' }}>Cuối kỳ</option>
                        <option value="practice" {{ request('type') == 'practice' ? 'selected' : '' }}>Luyện tập</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Tất cả</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã phát hành</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Đã lưu trữ</option>
                    </select>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        🔍 Lọc
                    </button>
                    <a href="{{ route('teacher.exams.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
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
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden">
            <!-- Card Header -->
            <div class="p-6 {{ $exam->status === 'published' ? 'bg-gradient-to-r from-green-500 to-emerald-500' : ($exam->status === 'draft' ? 'bg-gradient-to-r from-gray-500 to-slate-500' : 'bg-gradient-to-r from-orange-500 to-amber-500') }} text-white">
                <div class="flex items-center justify-between mb-2">
                    <span class="px-3 py-1 bg-white bg-opacity-30 rounded-full text-xs font-semibold">
                        @if($exam->type === 'quiz') 📝 Kiểm tra
                        @elseif($exam->type === 'midterm') 📚 Giữa kỳ
                        @elseif($exam->type === 'final') 🎓 Cuối kỳ
                        @else 💪 Luyện tập
                        @endif
                    </span>
                    <span class="px-3 py-1 bg-white bg-opacity-30 rounded-full text-xs font-semibold">
                        ⏱️ {{ $exam->duration }}p
                    </span>
                </div>
                <h3 class="text-lg font-bold">{{ $exam->title }}</h3>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <!-- Subject & Class -->
                <div class="mb-4 space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        {{ $exam->subject->name }}
                    </div>
                    @if($exam->classRoom)
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ $exam->classRoom->name }}
                    </div>
                    @endif
                </div>

                <!-- Stats -->
                <div class="mb-4 grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-indigo-600">{{ $exam->questions->count() }}</div>
                        <div class="text-xs text-gray-600">Câu hỏi</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $exam->total_points ?? 0 }}</div>
                        <div class="text-xs text-gray-600">Điểm</div>
                    </div>
                </div>

                <!-- Time -->
                @if($exam->start_time)
                <div class="mb-4 text-sm text-gray-600">
                    <div class="flex items-center mb-1">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Bắt đầu: {{ $exam->start_time->format('d/m/Y H:i') }}
                    </div>
                    @if($exam->end_time)
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Kết thúc: {{ $exam->end_time->format('d/m/Y H:i') }}
                    </div>
                    @endif
                </div>
                @endif

                <!-- Actions -->
                <div class="flex items-center space-x-2">
                    <a href="{{ route('teacher.exams.show', $exam) }}" class="flex-1 px-4 py-2 bg-indigo-600 text-white text-center rounded-lg hover:bg-indigo-700 transition-colors text-sm">
                        Chi tiết
                    </a>
                    <a href="{{ route('teacher.exams.edit', $exam) }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                        Sửa
                    </a>
                    <form action="{{ route('teacher.exams.destroy', $exam) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa đề thi này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm">
                            Xóa
                        </button>
                    </form>
                </div>
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
    <div class="bg-white rounded-xl shadow-md p-12 text-center">
        <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có đề thi nào</h3>
        <p class="text-gray-600 mb-6">Bắt đầu tạo đề thi đầu tiên cho học sinh của bạn</p>
        <a href="{{ route('teacher.exams.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tạo Đề thi Đầu tiên
        </a>
    </div>
    @endif
</div>
@endsection
