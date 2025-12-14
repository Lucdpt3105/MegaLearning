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

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-100 text-sm font-medium mb-1">Tổng số môn học</p>
                    <p class="text-4xl font-bold">{{ $subjects->count() }}</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <span class="text-3xl">📚</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Tổng chủ đề</p>
                    <p class="text-4xl font-bold">{{ $subjects->sum('topics_count') }}</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <span class="text-3xl">📖</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Tổng đề thi</p>
                    <p class="text-4xl font-bold">{{ $subjects->sum('exams_count') }}</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <span class="text-3xl">📝</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex items-center space-x-4 flex-1">
                <!-- Search -->
                <div class="relative flex-1 max-w-md">
                    <input type="text" 
                           id="searchInput"
                           placeholder="Tìm kiếm theo tên, mã môn học..." 
                           class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg">🔍</span>
                </div>
            </div>

            <a href="{{ route('admin.subjects.create') }}" 
               class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium shadow-sm hover:shadow-md">
                <span class="mr-2 text-lg">➕</span>
                Thêm môn học
            </a>
        </div>
    </div>

    <!-- Subjects Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        STT
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        📚 Môn học
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        📖 Chủ đề
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        📋 Đề thi
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        ⚡ Thao tác
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="subjectsTableBody">
                @forelse($subjects as $subject)
                <tr class="hover:bg-indigo-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-semibold text-gray-600">{{ $loop->iteration }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-lg mr-3 shadow-md">
                                {{ strtoupper(substr($subject->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $subject->name }}</p>
                                @if($subject->code)
                                <p class="text-xs text-gray-500 mt-0.5">🏷️ {{ $subject->code }}</p>
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
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.subjects.show', $subject->id) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition font-medium"
                               title="Xem chi tiết môn học">
                                <span class="mr-1">👁️</span> Xem
                            </a>
                            <a href="{{ route('admin.subjects.edit', $subject->id) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition font-medium"
                               title="Chỉnh sửa môn học">
                                <span class="mr-1">✏️</span> Sửa
                            </a>
                            <form action="{{ route('admin.subjects.destroy', $subject->id) }}" 
                                  method="POST" 
                                  class="inline-block"
                                  onsubmit="return confirm('⚠️ XÁC NHẬN XÓA MÔN HỌC\n\nTên: {{ $subject->name }}\nMã: {{ $subject->code }}\n\nBạn có chắc chắn muốn xóa môn học này?\n\nLưu ý: Dữ liệu sẽ được lưu trữ và có thể khôi phục sau này.\n\nNhấn OK để xóa, Cancel để hủy.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition font-medium"
                                        title="Xóa môn học">
                                    <span class="mr-1">🗑️</span> Xóa
                                </button>
                            </form>
                        </div>
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

    <!-- Search Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableBody = document.getElementById('subjectsTableBody');
            const rows = tableBody.querySelectorAll('tr');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();

                rows.forEach(row => {
                    // Skip empty state row
                    if (row.children.length === 1 && row.children[0].colSpan > 1) {
                        return;
                    }

                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Check if all rows are hidden
                const visibleRows = Array.from(rows).filter(row => {
                    return row.style.display !== 'none' && 
                           !(row.children.length === 1 && row.children[0].colSpan > 1);
                });

                // Show/hide no results message
                const existingNoResults = tableBody.querySelector('.no-results-row');
                if (visibleRows.length === 0 && searchTerm !== '') {
                    if (!existingNoResults) {
                        const noResultsRow = document.createElement('tr');
                        noResultsRow.className = 'no-results-row';
                        noResultsRow.innerHTML = `
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <div class="text-4xl mb-2">🔍</div>
                                    <p>Không tìm thấy môn học nào phù hợp với "${searchTerm}"</p>
                                </div>
                            </td>
                        `;
                        tableBody.appendChild(noResultsRow);
                    }
                } else if (existingNoResults) {
                    existingNoResults.remove();
                }
            });
        });
    </script>
@endsection
