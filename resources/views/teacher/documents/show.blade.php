@extends('layouts.app')

@section('title', $document->title)

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4 mb-4">
            <a href="{{ route('teacher.documents.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900">{{ $document->title }}</h1>
                <p class="text-gray-600 mt-1">Chi tiết tài liệu</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('teacher.documents.edit', $document) }}" class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Chỉnh sửa</span>
                </a>
                <form action="{{ route('teacher.documents.destroy', $document) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài liệu này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center space-x-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Xóa</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Rejection Alert -->
    @if($document->approval_status === 'rejected' && $document->rejection_reason)
    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-6">
        <div class="flex">
            <svg class="w-6 h-6 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-red-800">Tài liệu đã bị từ chối</h3>
                <p class="mt-2 text-sm text-red-700"><strong>Lý do:</strong> {{ $document->rejection_reason }}</p>
                <p class="mt-2 text-sm text-red-600">Vui lòng chỉnh sửa và tải lên phiên bản mới để gửi kiểm duyệt lại.</p>
                <div class="mt-4">
                    <a href="{{ route('teacher.documents.edit', $document) }}" class="inline-flex items-center space-x-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Cập nhật tài liệu</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Document Info Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Thông tin Tài liệu</h2>
                
                <!-- File Preview -->
                <div class="mb-6 p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-100">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 h-20 w-20 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            @if(str_contains($document->file_type, 'pdf'))
                                <span class="text-4xl">📄</span>
                            @elseif(str_contains($document->file_type, 'word'))
                                <span class="text-4xl">📝</span>
                            @elseif(str_contains($document->file_type, 'powerpoint') || str_contains($document->file_type, 'presentation'))
                                <span class="text-4xl">📊</span>
                            @elseif(str_contains($document->file_type, 'excel') || str_contains($document->file_type, 'spreadsheet'))
                                <span class="text-4xl">📈</span>
                            @else
                                <span class="text-4xl">📁</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900">{{ $document->file_name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ number_format($document->file_size / 1024, 2) }} KB • {{ $document->file_type }}</p>
                            <p class="text-sm text-gray-500 mt-1">Tải lên: {{ $document->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    @if($document->description)
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Mô tả</h3>
                        <p class="text-gray-900">{{ $document->description }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Môn học</h3>
                            <p class="text-gray-900">{{ $document->subject ? $document->subject->name : 'N/A' }}</p>
                            <p class="text-sm text-gray-500">{{ $document->subject->code }}</p>
                        </div>

                        @if($document->folder)
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Thư mục</h3>
                            <p class="text-gray-900">📁 {{ $document->folder }}</p>
                        </div>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Người tải lên</h3>
                        <p class="text-gray-900">{{ $document->uploader->name }}</p>
                        <p class="text-sm text-gray-500">{{ $document->uploader->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Download Button -->
            @if($document->approval_status === 'approved')
            <div class="bg-white rounded-xl shadow-md p-6">
                <a href="{{ route('teacher.documents.download', $document) }}" class="w-full inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-4 rounded-xl font-medium shadow-lg hover:shadow-xl transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Tải xuống Tài liệu</span>
                </a>
                <p class="text-center text-sm text-gray-500 mt-2">Đã tải xuống {{ $document->download_count }} lần</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Trạng thái</h2>
                
                <div class="space-y-3">
                    @if($document->approval_status === 'approved')
                        <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-green-800">Đã được phê duyệt</p>
                                <p class="text-xs text-green-600 mt-1">
                                    {{ $document->approved_at->format('d/m/Y H:i') }}
                                </p>
                                @if($document->approver)
                                <p class="text-xs text-green-600 mt-1">
                                    Bởi: {{ $document->approver->name }}
                                </p>
                                @endif
                            </div>
                        </div>
                    @elseif($document->approval_status === 'pending')
                        <div class="flex items-center space-x-3 p-3 bg-yellow-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-yellow-800">Đang chờ kiểm duyệt</p>
                                <p class="text-xs text-yellow-600 mt-1">
                                    Admin sẽ xem xét và phê duyệt sớm
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-3 p-3 bg-red-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-red-800">Đã bị từ chối</p>
                                <p class="text-xs text-red-600 mt-1">
                                    Cần cập nhật và gửi lại
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Stats Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Thống kê</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Lượt tải xuống</span>
                        <span class="text-lg font-bold text-indigo-600">{{ $document->download_count }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Ngày tạo</span>
                        <span class="text-sm font-medium text-gray-900">{{ $document->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Cập nhật cuối</span>
                        <span class="text-sm font-medium text-gray-900">{{ $document->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Thao tác nhanh</h2>
                
                <div class="space-y-2">
                    <!-- Approve/Reject Actions -->
                    @if($document->approval_status === 'pending')
                        <button onclick="approveDocument({{ $document->id }})" class="w-full inline-flex items-center justify-center space-x-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Phê duyệt Tài liệu</span>
                        </button>
                    @endif

                    @if($document->approval_status === 'approved')
                        <button onclick="rejectDocument({{ $document->id }})" class="w-full inline-flex items-center justify-center space-x-2 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            <span>Gỡ xuống Tài liệu</span>
                        </button>
                    @endif
                    
                    <a href="{{ route('teacher.documents.edit', $document) }}" class="w-full inline-flex items-center justify-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Chỉnh sửa</span>
                    </a>
                    
                    <a href="{{ route('teacher.documents.download', $document) }}" class="w-full inline-flex items-center justify-center space-x-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Tải xuống</span>
                    </a>
                    
                    <a href="{{ route('teacher.documents.index') }}" class="w-full inline-flex items-center justify-center space-x-2 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Quay lại danh sách</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function approveDocument(documentId) {
    if (confirm('Bạn có chắc chắn muốn phê duyệt tài liệu này? Tài liệu sẽ được công khai cho học sinh.')) {
        fetch(`/teacher/documents/${documentId}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert('Lỗi: ' + data.message);
            }
        })
        .catch(error => {
            alert('Có lỗi xảy ra: ' + error.message);
        });
    }
}

function rejectDocument(documentId) {
    const reason = prompt('Nhập lý do gỡ xuống tài liệu (tùy chọn):');
    if (reason !== null) {
        fetch(`/teacher/documents/${documentId}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert('Lỗi: ' + data.message);
            }
        })
        .catch(error => {
            alert('Có lỗi xảy ra: ' + error.message);
        });
    }
}
</script>
@endsection
