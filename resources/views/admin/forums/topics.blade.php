@extends('admin.layout')

@section('title', 'Chủ đề thảo luận')
@section('page-title', 'Quản lý Diễn đàn')
@section('page-description', 'Quản lý chủ đề thảo luận trong diễn đàn')

@section('content')

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium mb-1">Tổng chủ đề</p>
                <p class="text-4xl font-bold">{{ number_format($stats['total_threads']) }}</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <span class="text-3xl">💬</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium mb-1">Đang hoạt động</p>
                <p class="text-4xl font-bold">{{ number_format($stats['active_threads']) }}</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <span class="text-3xl">✅</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium mb-1">Chờ duyệt</p>
                <p class="text-4xl font-bold">{{ number_format($stats['pending_threads']) }}</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <span class="text-3xl">⏳</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium mb-1">Tổng bài viết</p>
                <p class="text-4xl font-bold">{{ number_format($stats['total_posts']) }}</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <span class="text-3xl">📝</span>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
    <form method="GET" action="{{ route('admin.forums.topics') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
            <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Tất cả</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                <option value="locked" {{ request('status') == 'locked' ? 'selected' : '' }}>Đã khóa</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Tìm theo tiêu đề..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i data-feather="search" class="w-4 h-4 inline mr-1"></i> Lọc
            </button>
            <a href="{{ route('admin.forums.topics') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                <i data-feather="x" class="w-4 h-4 inline"></i>
            </a>
        </div>
    </form>
</div>

<!-- Topics Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">
            <i data-feather="message-square" class="w-5 h-5 inline text-blue-600"></i>
            Danh sách Chủ đề
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiêu đề</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Môn học</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người tạo</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bài viết</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Lượt xem</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($threads as $thread)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            @if($thread->is_pinned)
                                <span class="text-yellow-500" title="Đã ghim">📌</span>
                            @endif
                            @if($thread->is_locked)
                                <span class="text-red-500" title="Đã khóa">🔒</span>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $thread->title }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $thread->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $thread->subject ? $thread->subject->name : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $thread->creator ? $thread->creator->name : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-center text-sm text-gray-900 font-semibold">
                        {{ $thread->posts_count }}
                    </td>
                    <td class="px-6 py-4 text-center text-sm text-gray-600">
                        {{ number_format($thread->view_count) }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs rounded-full font-semibold
                            {{ $thread->status == 'active' ? 'bg-green-100 text-green-700' : 
                               ($thread->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                               ($thread->status == 'locked' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700')) }}">
                            @if($thread->status == 'active') Hoạt động
                            @elseif($thread->status == 'pending') Chờ duyệt
                            @elseif($thread->status == 'locked') Đã khóa
                            @else {{ ucfirst($thread->status) }}
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            @if($thread->status == 'pending')
                                <form action="{{ route('admin.forums.threads.approve', $thread->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800" title="Phê duyệt">
                                        <i data-feather="check" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.forums.threads.reject', $thread->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Từ chối">
                                        <i data-feather="x" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('admin.forums.threads.toggle-pin', $thread->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-yellow-600 hover:text-yellow-800" title="{{ $thread->is_pinned ? 'Bỏ ghim' : 'Ghim' }}">
                                    <i data-feather="bookmark" class="w-4 h-4"></i>
                                </button>
                            </form>

                            <form action="{{ route('admin.forums.threads.toggle-lock', $thread->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-orange-600 hover:text-orange-800" title="{{ $thread->is_locked ? 'Mở khóa' : 'Khóa' }}">
                                    <i data-feather="lock" class="w-4 h-4"></i>
                                </button>
                            </form>

                            <form action="{{ route('admin.forums.threads.delete', $thread->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa chủ đề này?')">
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
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i data-feather="message-square" class="w-16 h-16 mx-auto mb-4 text-gray-400"></i>
                        <p class="text-lg">Chưa có chủ đề thảo luận nào</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($threads->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $threads->links() }}
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    feather.replace();
</script>
@endpush
