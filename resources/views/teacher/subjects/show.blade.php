@extends('layouts.app')

@section('title', $subject->name)

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4 mb-4">
            <a href="{{ route('teacher.subjects.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div class="flex-1">
                <div class="flex items-center space-x-3">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $subject->name }}</h1>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $subject->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $subject->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $subject->status === 'archived' ? 'bg-orange-100 text-orange-800' : '' }}">
                        {{ $subject->status === 'active' ? '✅ Hoạt động' : '' }}
                        {{ $subject->status === 'draft' ? '📝 Nháp' : '' }}
                        {{ $subject->status === 'archived' ? '📦 Lưu trữ' : '' }}
                    </span>
                </div>
                <p class="text-gray-600 mt-1">Mã: {{ $subject->code }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('teacher.subjects.edit', $subject) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Chỉnh sửa</span>
                </a>
            </div>
        </div>

        @if($subject->description)
        <div class="bg-white rounded-xl p-6 shadow-md">
            <p class="text-gray-700">{{ $subject->description }}</p>
        </div>
        @endif
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <p class="text-green-700 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Lớp học</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ $subject->classRooms->count() }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Tài liệu</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $subject->documents->count() }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Đề thi</p>
                    <p class="text-3xl font-bold text-purple-600 mt-1">{{ $subject->exams->count() }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Chủ đề</p>
                    <p class="text-3xl font-bold text-orange-600 mt-1">{{ $subject->topics->count() }}</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Rooms List -->
    @if($subject->classRooms->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900">Danh sách lớp học</h2>
            <a href="{{ route('teacher.students.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                Xem tất cả →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($subject->classRooms as $classRoom)
            <a href="{{ route('teacher.students.show', $classRoom) }}" class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 hover:shadow-lg transition-all duration-200 border-2 border-transparent hover:border-indigo-400">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $classRoom->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $classRoom->code }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $classRoom->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $classRoom->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $classRoom->status === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $classRoom->status === 'archived' ? 'bg-orange-100 text-orange-800' : '' }}">
                        {{ $classRoom->status === 'active' ? '✅ Hoạt động' : '' }}
                        {{ $classRoom->status === 'draft' ? '📝 Nháp' : '' }}
                        {{ $classRoom->status === 'completed' ? '✔️ Hoàn thành' : '' }}
                        {{ $classRoom->status === 'archived' ? '📦 Lưu trữ' : '' }}
                    </span>
                </div>

                @if($classRoom->description)
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $classRoom->description }}</p>
                @endif

                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-700">
                            {{ $classRoom->students_count ?? 0 }}/{{ $classRoom->max_students }} học sinh
                        </span>
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $classRoom->start_date?->format('d/m/Y') ?? 'Chưa bắt đầu' }}
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Documents List -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900">Tài liệu đã đăng</h2>
            <a href="{{ route('teacher.documents.index', ['subject_id' => $subject->id]) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                Xem tất cả →
            </a>
        </div>

        @if($subject->documents->isEmpty())
            <div class="text-center py-12">
                <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-gray-500 mb-4">Chưa có tài liệu nào</p>
                <a href="{{ route('teacher.documents.create', ['subject_id' => $subject->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Thêm tài liệu đầu tiên
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên tài liệu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thư mục</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lượt tải</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tải lên</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($subject->documents->take(10) as $document)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @php
                                            $fileTypeColors = [
                                                'pdf' => 'bg-red-100 text-red-600',
                                                'doc' => 'bg-blue-100 text-blue-600',
                                                'docx' => 'bg-blue-100 text-blue-600',
                                                'ppt' => 'bg-orange-100 text-orange-600',
                                                'pptx' => 'bg-orange-100 text-orange-600',
                                                'xls' => 'bg-green-100 text-green-600',
                                                'xlsx' => 'bg-green-100 text-green-600',
                                            ];
                                            $extension = strtolower(pathinfo($document->file_name, PATHINFO_EXTENSION));
                                            $colorClass = $fileTypeColors[$extension] ?? 'bg-gray-100 text-gray-600';
                                        @endphp
                                        <div class="{{ $colorClass }} rounded-lg p-2 flex items-center justify-center">
                                            <span class="text-xs font-bold uppercase">{{ $extension }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($document->file_name, 40) }}</div>
                                        <div class="text-sm text-gray-500">{{ number_format($document->file_size / 1024, 2) }} KB</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ strtoupper($document->file_type) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $document->folder ?? 'Chung' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($document->approval_status === 'approved')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        ✓ Đã duyệt
                                    </span>
                                @elseif($document->approval_status === 'rejected')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        ✗ Từ chối
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        ⏱ Chờ duyệt
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $document->download_count ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $document->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('teacher.documents.show', $document) }}" class="text-indigo-600 hover:text-indigo-900" title="Xem chi tiết">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('teacher.documents.download', $document) }}" class="text-green-600 hover:text-green-900" title="Tải xuống">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </a>
                                    @if($document->approval_status === 'pending')
                                    <button onclick="approveDocument({{ $document->id }})" class="text-blue-600 hover:text-blue-900" title="Phê duyệt">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($subject->documents->count() > 10)
            <div class="mt-4 text-center">
                <a href="{{ route('teacher.documents.index', ['subject_id' => $subject->id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Xem thêm {{ $subject->documents->count() - 10 }} tài liệu
                </a>
            </div>
            @endif
        @endif
    </div>

</div>

@push('scripts')
<script>
function approveDocument(documentId) {
    if (!confirm('Bạn có chắc muốn phê duyệt tài liệu này?')) {
        return;
    }
    
    fetch(`/teacher/documents/${documentId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi phê duyệt tài liệu');
    });
}
</script>
@endpush

@endsection
