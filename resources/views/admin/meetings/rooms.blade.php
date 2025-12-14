@extends('admin.layout')

@section('title', 'Phòng họp')
@section('page-title', 'Quản lý Phòng họp')
@section('page-description', 'Quản lý các phòng họp và video call')

@push('styles')
<style>
    .meeting-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1.25rem;
        margin-bottom: 0.75rem;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }
    .meeting-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transform: translateY(-1px);
    }
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>
@endpush

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
                <p class="text-blue-100 text-sm font-medium mb-1">Tổng phòng họp</p>
                <p class="text-4xl font-bold">{{ number_format($stats['total_meetings']) }}</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <span class="text-3xl">📹</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium mb-1">Đang hoạt động</p>
                <p class="text-4xl font-bold">{{ number_format($stats['active_meetings']) }}</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <span class="text-3xl">✅</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium mb-1">Đã kết thúc</p>
                <p class="text-4xl font-bold">{{ number_format($stats['completed_meetings']) }}</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <span class="text-3xl">🏁</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium mb-1">Tổng thời lượng</p>
                <p class="text-4xl font-bold">{{ number_format($stats['total_duration']) }}</p>
                <p class="text-xs text-orange-100 mt-1">phút</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                <span class="text-3xl">⏱️</span>
            </div>
        </div>
    </div>
</div>

<!-- Create New Meeting Button -->
<div class="mb-6">
    <button onclick="document.getElementById('createModal').classList.remove('hidden')" 
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
        <i data-feather="plus" class="w-5 h-5 inline mr-2"></i>
        Tạo phòng họp mới
    </button>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
    <form action="{{ route('admin.meetings.rooms') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Lớp học</label>
            <select name="class_room_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Tất cả</option>
                @foreach($classRooms as $class)
                    <option value="{{ $class->id }}" {{ request('class_room_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }} - {{ $class->subject->name ?? 'N/A' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Tất cả</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang họp</option>
                <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Đã kết thúc</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Từ ngày</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Đến ngày</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i data-feather="search" class="w-4 h-4 inline mr-1"></i> Lọc
            </button>
            <a href="{{ route('admin.meetings.rooms') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                <i data-feather="x" class="w-4 h-4 inline"></i>
            </a>
        </div>
    </form>
</div>

<!-- Meetings List -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        <i data-feather="video" class="w-5 h-5 inline text-blue-600"></i>
        Danh sách Phòng họp
    </h3>

    @forelse($meetings as $meeting)
        <div class="meeting-card">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h4 class="text-lg font-semibold text-gray-800">{{ $meeting->title }}</h4>
                        <span class="status-badge {{ $meeting->status == 'active' ? 'bg-green-100 text-green-700' : ($meeting->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : ($meeting->status == 'ended' ? 'bg-gray-100 text-gray-700' : 'bg-red-100 text-red-700')) }}">
                            {{ $meeting->status == 'active' ? 'Đang họp' : ($meeting->status == 'pending' ? 'Đang chờ' : ($meeting->status == 'ended' ? 'Đã kết thúc' : 'Đã hủy')) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600 mb-3">
                        <div>
                            <i data-feather="book-open" class="w-4 h-4 inline text-gray-400"></i>
                            <span class="ml-1">{{ $meeting->classRoom ? $meeting->classRoom->name : 'N/A' }}</span>
                        </div>
                        <div>
                            <i data-feather="user" class="w-4 h-4 inline text-gray-400"></i>
                            <span class="ml-1">{{ $meeting->host ? $meeting->host->name : 'N/A' }}</span>
                        </div>
                        <div>
                            <i data-feather="calendar" class="w-4 h-4 inline text-gray-400"></i>
                            <span class="ml-1">{{ $meeting->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <i data-feather="clock" class="w-4 h-4 inline text-gray-400"></i>
                            <span class="ml-1">{{ $meeting->duration }} phút</span>
                        </div>
                    </div>

                    @if($meeting->description)
                        <p class="text-sm text-gray-600 mb-2">{{ $meeting->description }}</p>
                    @endif

                    @if($meeting->meeting_url)
                        <a href="{{ $meeting->meeting_url }}" target="_blank" 
                           class="text-sm text-blue-600 hover:text-blue-800">
                            <i data-feather="link" class="w-4 h-4 inline"></i>
                            {{ $meeting->meeting_url }}
                        </a>
                    @endif
                </div>

                <div class="flex gap-2">
                    @if($meeting->status != 'ended' && $meeting->status != 'cancelled')
                        <form action="{{ route('admin.meetings.updateStatus', $meeting->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $meeting->status == 'pending' ? 'active' : 'ended' }}">
                            <button type="submit" class="px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                                {{ $meeting->status == 'pending' ? 'Bắt đầu' : 'Kết thúc' }}
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('admin.meetings.destroy', $meeting->id) }}" method="POST" class="inline" 
                          onsubmit="return confirm('Bạn có chắc muốn xóa phòng họp này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                            Xóa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12 text-gray-500">
            <i data-feather="video-off" class="w-16 h-16 mx-auto mb-4 text-gray-400"></i>
            <p class="text-lg">Chưa có phòng họp nào</p>
        </div>
    @endforelse

    @if($meetings->hasPages())
        <div class="mt-6">
            {{ $meetings->links() }}
        </div>
    @endif
</div>

<!-- Create Meeting Modal -->
<div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-semibold mb-4">Tạo phòng họp mới</h3>
        
        <form action="{{ route('admin.meetings.rooms.create') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tiêu đề *</label>
                    <input type="text" name="title" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lớp học *</label>
                    <select name="class_room_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Chọn lớp --</option>
                        @foreach($classRooms as $class)
                            <option value="{{ $class->id }}">
                                {{ $class->name }} - {{ $class->subject->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian dự kiến (phút)</label>
                    <input type="number" name="duration" min="0" value="60"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Tạo phòng họp
                </button>
                <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Hủy
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    feather.replace();
</script>
@endpush
