@extends('admin.layout')

@section('title', 'Quản lý đề thi')
@section('page-title', 'Quản lý đề thi')
@section('page-description', 'Danh sách và quản lý tất cả đề thi (Admin + Teacher)')

@section('content')

<!-- Success/Error Messages -->
@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif

<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Tổng đề thi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $exams->total() }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Đang hoạt động</p>
                    <p class="text-2xl font-bold text-green-600">{{ \App\Models\Exam::where('status', 'published')->count() }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Nháp</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ \App\Models\Exam::where('status', 'draft')->count() }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Đã lưu trữ</p>
                    <p class="text-2xl font-bold text-gray-600">{{ \App\Models\Exam::where('status', 'archived')->count() }}</p>
                </div>
                <div class="p-3 bg-gray-100 rounded-lg">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form method="GET" action="{{ route('admin.exams.index') }}" class="flex flex-wrap items-center justify-between gap-4">
            <!-- Search & Filter -->
            <div class="flex flex-wrap items-center gap-3 flex-1">
                <div class="relative">
                    <input type="text" 
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Tìm kiếm đề thi..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-64">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Tất cả trạng thái</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đang hoạt động</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Đã lưu trữ</option>
                </select>

                <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Tất cả loại</option>
                    <option value="quiz" {{ request('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                    <option value="midterm" {{ request('type') == 'midterm' ? 'selected' : '' }}>Giữa kỳ</option>
                    <option value="final" {{ request('type') == 'final' ? 'selected' : '' }}>Cuối kỳ</option>
                    <option value="practice" {{ request('type') == 'practice' ? 'selected' : '' }}>Luyện tập</option>
                </select>

                <select name="subject_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Tất cả môn học</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>

                <select name="teacher_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Tất cả giáo viên</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                    Lọc
                </button>
                <a href="{{ route('admin.exams.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Xóa bộ lọc
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Đề thi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Giáo viên</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Môn học</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Loại</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Câu hỏi</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Bài nộp</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($exams as $exam)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-4">
                            <div>
                                <p class="font-medium text-gray-900">{{ $exam->title }}</p>
                                <p class="text-sm text-gray-500">{{ Str::limit($exam->description, 50) }}</p>
                                @if($exam->start_time && $exam->end_time)
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ \Carbon\Carbon::parse($exam->start_time)->format('d/m/Y H:i') }} - 
                                    {{ \Carbon\Carbon::parse($exam->end_time)->format('d/m/Y H:i') }}
                                </p>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700">
                            {{ $exam->creator->name ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700">
                            {{ $exam->subject->name ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $exam->type === 'quiz' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $exam->type === 'midterm' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $exam->type === 'final' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $exam->type === 'practice' ? 'bg-green-100 text-green-800' : '' }}">
                                {{ ucfirst($exam->type) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            {{ $exam->questions->count() }} câu
                        </td>
                        <td class="px-4 py-4 text-center text-sm text-gray-700">
                            {{ $exam->submissions->count() }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $exam->status === 'published' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $exam->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $exam->status === 'archived' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ $exam->status === 'published' ? 'Hoạt động' : '' }}
                                {{ $exam->status === 'draft' ? 'Nháp' : '' }}
                                {{ $exam->status === 'archived' ? 'Lưu trữ' : '' }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2 justify-center">
                                {{-- Xem chi tiết --}}
                                <a href="{{ route('admin.exams.show', $exam) }}" 
                                   class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition"
                                   title="Xem chi tiết">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </a>

                                {{-- Câu hỏi --}}
                                <a href="{{ route('admin.exams.questions', $exam) }}" 
                                   class="p-2 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-lg transition"
                                   title="Quản lý câu hỏi">
                                    <i data-feather="help-circle" class="w-4 h-4"></i>
                                </a>

                                {{-- Kết quả --}}
                                <a href="{{ route('admin.exams.results', $exam) }}" 
                                   class="p-2 bg-green-50 text-green-600 hover:bg-green-100 rounded-lg transition"
                                   title="Xem kết quả">
                                    <i data-feather="bar-chart-2" class="w-4 h-4"></i>
                                </a>

                                {{-- Sửa --}}
                                <a href="{{ route('admin.exams.edit', $exam) }}" 
                                   class="p-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg transition"
                                   title="Chỉnh sửa">
                                    <i data-feather="edit-2" class="w-4 h-4"></i>
                                </a>

                                {{-- Xóa --}}
                                <form action="{{ route('admin.exams.destroy', $exam) }}" method="POST" 
                                      onsubmit="return confirm('Bạn có chắc muốn xóa đề thi này? Hành động không thể hoàn tác!');"
                                      class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition"
                                            title="Xóa">
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="mt-4 text-sm">Chưa có đề thi nào</p>
                            <p class="mt-1 text-xs text-gray-400">Tất cả đề thi từ giáo viên sẽ hiển thị tại đây</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($exams->hasPages())
    <div class="flex justify-center">
        {{ $exams->links() }}
    </div>
    @endif
</div>

@endsection
