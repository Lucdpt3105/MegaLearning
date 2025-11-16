@extends('layouts.app')

@section('title', 'Quản lý Môn học')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Quản lý Môn học</h1>
            <p class="text-gray-600 mt-2">UC-GV-014: Xem danh sách môn học của bạn</p>
        </div>
        <a href="{{ route('teacher.subjects.create') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold px-6 py-3 rounded-xl shadow-md transition-all duration-200 transform hover:scale-105 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Thêm Môn học Mới</span>
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

    <!-- Subjects List -->
    @if($subjects->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($subjects as $subject)
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <!-- Card Header -->
            <div class="bg-gradient-to-r {{ $subject->status === 'active' ? 'from-indigo-500 to-purple-500' : ($subject->status === 'draft' ? 'from-gray-400 to-gray-500' : 'from-orange-400 to-red-500') }} p-6 text-white">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold mb-1">{{ $subject->name }}</h3>
                        <p class="text-sm opacity-90">{{ $subject->code }}</p>
                    </div>
                    <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-xs font-semibold">
                        @if($subject->status === 'active')
                            ✅ Hoạt động
                        @elseif($subject->status === 'draft')
                            📝 Nháp
                        @else
                            📦 Lưu trữ
                        @endif
                    </span>
                </div>
                
                @if($subject->description)
                <p class="text-sm opacity-90 line-clamp-2">{{ $subject->description }}</p>
                @endif
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <!-- Stats -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="text-center p-3 bg-blue-50 rounded-xl">
                        <p class="text-2xl font-bold text-blue-600">{{ $subject->class_rooms_count }}</p>
                        <p class="text-xs text-gray-600 mt-1">Lớp học</p>
                    </div>
                    <div class="text-center p-3 bg-purple-50 rounded-xl">
                        <p class="text-2xl font-bold text-purple-600">{{ $subject->exams_count }}</p>
                        <p class="text-xs text-gray-600 mt-1">Đề thi</p>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded-xl">
                        <p class="text-2xl font-bold text-green-600">{{ $subject->documents_count }}</p>
                        <p class="text-xs text-gray-600 mt-1">Tài liệu</p>
                    </div>
                    <div class="text-center p-3 bg-orange-50 rounded-xl">
                        <p class="text-2xl font-bold text-orange-600">{{ $subject->topics_count }}</p>
                        <p class="text-xs text-gray-600 mt-1">Chủ đề</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-2">
                    <a href="{{ route('teacher.subjects.show', $subject) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded-lg text-sm font-medium transition-colors">
                        Xem chi tiết
                    </a>
                    <a href="{{ route('teacher.subjects.edit', $subject) }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <form action="{{ route('teacher.subjects.destroy', $subject) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa môn học này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $subjects->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Chưa có môn học nào</h3>
        <p class="text-gray-600 mb-6">Bắt đầu bằng cách tạo môn học đầu tiên của bạn!</p>
        <a href="{{ route('teacher.subjects.create') }}" class="inline-flex items-center space-x-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold px-6 py-3 rounded-xl shadow-md transition-all duration-200 transform hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Tạo Môn học Đầu tiên</span>
        </a>
    </div>
    @endif
</div>
@endsection
