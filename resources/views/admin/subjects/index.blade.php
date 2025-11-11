@extends('admin.layout')

@section('title', 'Quản lý Môn học')
@section('page-title', 'Quản lý Môn học')
@section('page-description', 'Danh sách tất cả các môn học trong hệ thống')

@section('content')
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
                <!-- Data will be loaded via JavaScript -->
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <div class="text-4xl mb-2">⏳</div>
                            <p>Đang tải dữ liệu...</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex items-center justify-between">
        <p class="text-sm text-gray-500">
            Hiển thị <span id="showingFrom">0</span> đến <span id="showingTo">0</span> trong tổng số <span id="totalRecords">0</span> bản ghi
        </p>
        <div class="flex space-x-2" id="pagination">
            <!-- Pagination buttons will be generated here -->
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let subjects = [];
    let currentPage = 1;
    const perPage = 10;

    // Fetch subjects from API
    async function fetchSubjects() {
        try {
            const response = await fetch('/api/v1/subjects');
            const data = await response.json();
            
            if (data.success) {
                subjects = data.data;
                renderTable();
                updatePagination();
            }
        } catch (error) {
            console.error('Error fetching subjects:', error);
            document.getElementById('subjectsTableBody').innerHTML = `
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-red-500">
                        <div class="flex flex-col items-center">
                            <div class="text-4xl mb-2">❌</div>
                            <p>Lỗi khi tải dữ liệu. Vui lòng thử lại.</p>
                        </div>
                    </td>
                </tr>
            `;
        }
    }

    // Render table
    function renderTable() {
        const tbody = document.getElementById('subjectsTableBody');
        const start = (currentPage - 1) * perPage;
        const end = start + perPage;
        const paginatedData = subjects.slice(start, end);

        if (paginatedData.length === 0) {
            tbody.innerHTML = `
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
            `;
            return;
        }

        tbody.innerHTML = paginatedData.map(subject => `
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-medium text-gray-900">#${subject.subject_id}</span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold mr-3">
                            ${subject.subject_name.charAt(0)}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">${subject.subject_name}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        ${subject.topics_count || 0} chủ đề
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        ${subject.exams_count || 0} đề thi
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                    <a href="/admin/subjects/${subject.subject_id}" 
                       class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                        👁️ Xem
                    </a>
                    <a href="/admin/subjects/${subject.subject_id}/edit" 
                       class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition">
                        ✏️ Sửa
                    </a>
                    <button onclick="deleteSubject(${subject.subject_id})" 
                            class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                        🗑️ Xóa
                    </button>
                </td>
            </tr>
        `).join('');

        // Update showing info
        document.getElementById('showingFrom').textContent = start + 1;
        document.getElementById('showingTo').textContent = Math.min(end, subjects.length);
        document.getElementById('totalRecords').textContent = subjects.length;
    }

    // Update pagination
    function updatePagination() {
        const totalPages = Math.ceil(subjects.length / perPage);
        const pagination = document.getElementById('pagination');
        
        let html = '';
        
        // Previous button
        html += `
            <button onclick="changePage(${currentPage - 1})" 
                    ${currentPage === 1 ? 'disabled' : ''}
                    class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed">
                ← Trước
            </button>
        `;
        
        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                html += `
                    <button onclick="changePage(${i})" 
                            class="px-4 py-2 border rounded-lg transition ${i === currentPage ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-300 hover:bg-gray-50'}">
                        ${i}
                    </button>
                `;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                html += '<span class="px-2">...</span>';
            }
        }
        
        // Next button
        html += `
            <button onclick="changePage(${currentPage + 1})" 
                    ${currentPage === totalPages ? 'disabled' : ''}
                    class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed">
                Sau →
            </button>
        `;
        
        pagination.innerHTML = html;
    }

    // Change page
    function changePage(page) {
        const totalPages = Math.ceil(subjects.length / perPage);
        if (page < 1 || page > totalPages) return;
        
        currentPage = page;
        renderTable();
        updatePagination();
    }

    // Delete subject
    async function deleteSubject(id) {
        if (!confirm('Bạn có chắc chắn muốn xóa môn học này?')) return;
        
        try {
            const response = await fetch(`/api/v1/subjects/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                alert('Xóa môn học thành công!');
                fetchSubjects(); // Reload data
            } else {
                alert(data.message || 'Có lỗi xảy ra khi xóa môn học.');
            }
        } catch (error) {
            console.error('Error deleting subject:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
        }
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        const filtered = subjects.filter(subject => 
            subject.subject_name.toLowerCase().includes(searchTerm)
        );
        
        subjects = filtered.length > 0 ? filtered : subjects;
        currentPage = 1;
        renderTable();
        updatePagination();
        
        if (e.target.value === '') {
            fetchSubjects(); // Reset to original data
        }
    });

    // Load data on page load
    fetchSubjects();
</script>
@endpush
