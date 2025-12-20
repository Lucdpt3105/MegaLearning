@extends('admin.layout')

@section('title', 'Quản lý file')

@section('content')
<div class="p-6">
    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Quản lý Tài liệu 📁</h1>
                <p class="text-gray-600 mt-1">Quản lý tất cả file và tài liệu học tập</p>
            </div>
            <a href="{{ route('admin.files.upload') }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-lg hover:shadow-xl transition-all">
                <i data-feather="upload" class="w-5 h-5 mr-2"></i>
                Tải lên file
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Tổng file</p>
                    <p class="text-4xl font-bold">{{ number_format($stats['total_files']) }}</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i data-feather="file" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Dung lượng</p>
                    <p class="text-4xl font-bold">{{ number_format($stats['total_size'] / 1048576, 1) }}</p>
                    <p class="text-purple-100 text-xs mt-1">MB</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i data-feather="hard-drive" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium mb-1">Chờ duyệt</p>
                    <p class="text-4xl font-bold">{{ number_format($stats['pending_files']) }}</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i data-feather="clock" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Lượt tải</p>
                    <p class="text-4xl font-bold">{{ number_format($stats['total_downloads']) }}</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i data-feather="download" class="w-8 h-8"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.files.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Môn học</label>
                <select name="subject_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Tất cả</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Người tải</label>
                <select name="uploaded_by" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Tất cả</option>
                    @foreach($uploaders as $uploader)
                        <option value="{{ $uploader->id }}" {{ request('uploaded_by') == $uploader->id ? 'selected' : '' }}>
                            {{ $uploader->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Loại</label>
                <select name="folder" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Tất cả</option>
                    <option value="general" {{ request('folder') == 'general' ? 'selected' : '' }}>Chung</option>
                    <option value="lecture" {{ request('folder') == 'lecture' ? 'selected' : '' }}>Bài giảng</option>
                    <option value="exam" {{ request('folder') == 'exam' ? 'selected' : '' }}>Đề thi</option>
                    <option value="homework" {{ request('folder') == 'homework' ? 'selected' : '' }}>Bài tập</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Tên file..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition inline-flex items-center justify-center gap-2">
                    <i data-feather="filter" class="w-4 h-4"></i>
                    Lọc
                </button>
            </div>
        </form>
    </div>

    <!-- Files Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i data-feather="folder" class="w-5 h-5 inline text-blue-600"></i>
                Danh sách Tài liệu
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tên file</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loại</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kích thước</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Người tải</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lượt tải</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày tải</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($documents as $doc)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <i data-feather="file-text" class="w-5 h-5 text-gray-400 mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $doc->title }}</div>
                                    <div class="text-xs text-gray-500">{{ $doc->file_name }}</div>
                                    @if($doc->subject)
                                        <span class="inline-block mt-1 px-2 py-0.5 text-xs bg-blue-100 text-blue-700 rounded">{{ $doc->subject->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ 
                                $doc->folder === 'lecture' ? 'bg-purple-100 text-purple-700' : 
                                ($doc->folder === 'exam' ? 'bg-red-100 text-red-700' : 
                                ($doc->folder === 'homework' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700'))
                            }}">
                                {{ ucfirst($doc->folder) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ number_format($doc->file_size / 1024, 1) }} KB</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $doc->uploader ? $doc->uploader->name : 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ number_format($doc->download_count) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $doc->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.files.download', $doc->id) }}" 
                                   class="text-blue-600 hover:text-blue-800" title="Tải xuống">
                                    <i data-feather="download" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.files.destroy', $doc->id) }}" method="POST" 
                                      onsubmit="return confirm('Xác nhận xóa file này?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Xóa">
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i data-feather="inbox" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                            <p class="text-gray-600 text-lg">Chưa có tài liệu nào</p>
                            <p class="text-gray-500 text-sm mt-1">Tải lên file đầu tiên để bắt đầu</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($documents->hasPages())
        <div class="p-6 border-t border-gray-200">
            {{ $documents->links() }}
        </div>
        @endif
    </div>
</div>

<script>
feather.replace();
</script>
@endsection
