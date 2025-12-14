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
                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Đã lên lịch</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Đang họp</option>
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
                        <span class="status-badge {{ $meeting->status == 'in_progress' ? 'bg-green-100 text-green-700' : ($meeting->status == 'scheduled' ? 'bg-yellow-100 text-yellow-700' : ($meeting->status == 'ended' ? 'bg-gray-100 text-gray-700' : 'bg-red-100 text-red-700')) }}">
                            {{ $meeting->status == 'in_progress' ? 'Đang họp' : ($meeting->status == 'scheduled' ? 'Đã lên lịch' : ($meeting->status == 'ended' ? 'Đã kết thúc' : 'Đã hủy')) }}
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
                            <input type="hidden" name="status" value="{{ $meeting->status == 'scheduled' ? 'in_progress' : 'ended' }}">
                            <button type="submit" class="px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                                {{ $meeting->status == 'scheduled' ? 'Bắt đầu' : 'Kết thúc' }}
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
<div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-bold text-gray-900">Tạo phòng học mới</h3>
                <button onclick="document.getElementById('createModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-600">
                    <i data-feather="x" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <form action="{{ route('admin.meetings.rooms.create') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tên buổi học <span class="text-red-500">*</span></label>
                <input type="text" name="title" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                       placeholder="Ví dụ: Bài giảng chương 3">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lớp học <span class="text-red-500">*</span></label>
                <select name="class_room_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Chọn lớp học --</option>
                    @foreach($classRooms as $class)
                        <option value="{{ $class->id }}">
                            {{ $class->name }} - {{ $class->subject->name ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Giáo viên chủ trì <span class="text-red-500">*</span></label>
                <select name="host_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Chọn giáo viên --</option>
                    @foreach(\App\Models\User::role('teacher')->get() as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian bắt đầu <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="scheduled_at" required
                           value="{{ now()->addHours(1)->format('Y-m-d\TH:i') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Thời lượng (phút)</label>
                    <input type="number" name="duration" value="60" min="15" max="480"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nền tảng <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition">
                        <input type="radio" name="platform" value="zoom" checked class="mr-3 w-4 h-4 text-blue-600">
                        <div>
                            <div class="font-semibold text-gray-900">Zoom</div>
                            <div class="text-xs text-gray-500">Chuyên nghiệp, nhiều tính năng</div>
                        </div>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition">
                        <input type="radio" name="platform" value="jitsi" class="mr-3 w-4 h-4 text-blue-600">
                        <div>
                            <div class="font-semibold text-gray-900">Jitsi</div>
                            <div class="text-xs text-gray-500">Mã nguồn mở, miễn phí</div>
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                          placeholder="Nội dung buổi học, tài liệu cần chuẩn bị..."></textarea>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                    <i data-feather="video" class="w-4 h-4 inline mr-2"></i>
                    Tạo phòng học
                </button>
                <button type="button" 
                        onclick="document.getElementById('createModal').classList.add('hidden')"
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium transition">
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
