@extends('admin.layout')

@section('title', 'Quản lý Môn học')
@section('page-title', 'Quản lý Môn học')
@section('page-description', 'Danh sách tất cả các môn học trong hệ thống')

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

    <!-- Header Actions -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <!-- Search -->
            <div class="relative">
                <input type="text" 
                       id="searchInput"
                       placeholder="Tìm kiếm môn học..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-80">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
            </div>
        </div>

        <a href="{{ route('admin.subjects.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
            <span class="mr-2">➕</span>
            Thêm môn học
        </a>
    </div>

    <!-- Subjects Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tên môn học
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Số chủ đề
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Số đề thi
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Thao tác
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="subjectsTableBody">
                @forelse($subjects as $subject)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-medium text-gray-900">#{{ $subject->id }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold mr-3">
                                {{ strtoupper(substr($subject->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $subject->name }}</p>
                                @if($subject->code)
                                <p class="text-xs text-gray-500">{{ $subject->code }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $subject->topics_count ?? 0 }} chủ đề
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                            {{ $subject->exams_count ?? 0 }} đề thi
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        <a href="{{ route('admin.subjects.show', $subject->id) }}" 
                           class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                            👁️ Xem
                        </a>
                        <a href="{{ route('admin.subjects.edit', $subject->id) }}" 
                           class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition">
                            ✏️ Sửa
                        </a>
                        <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa môn học này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                                🗑️ Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <div class="text-4xl mb-2">📚</div>
                            <p>Chưa có môn học nào.</p>
                            <a href="{{ route('admin.subjects.create') }}" class="mt-4 text-indigo-600 hover:text-indigo-800 font-medium">
                                + Thêm môn học đầu tiên
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex items-center justify-between">
        <p class="text-sm text-gray-500">
            Tổng số {{ $subjects->count() }} môn học
        </p>
    </div>
@endsection
