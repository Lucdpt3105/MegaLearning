@extends('admin.layout')

@section('title', 'Quản lý Phòng học Trực tuyến')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Phòng học Trực tuyến 🎥</h1>
                <p class="text-gray-600 mt-1">Quản lý tất cả các buổi học online</p>
            </div>
            <button onclick="document.getElementById('createModal').classList.remove('hidden')" 
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-lg hover:shadow-xl transition-all">
                <i data-feather="plus" class="w-5 h-5 mr-2"></i>
                Tạo phòng học mới
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Tổng số buổi</p>
                    <p class="text-4xl font-bold">{{ number_format($stats['total_meetings']) }}</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i data-feather="video" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium mb-1">Đã lên lịch</p>
                    <p class="text-4xl font-bold">{{ number_format($stats['scheduled_meetings']) }}</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i data-feather="clock" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Đang diễn ra</p>
                    <p class="text-4xl font-bold">{{ number_format($stats['active_meetings']) }}</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i data-feather="radio" class="w-8 h-8"></i>
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
                    <i data-feather="check-circle" class="w-8 h-8"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium mb-1">Tổng thời lượng</p>
                    <p class="text-4xl font-bold">{{ number_format($stats['total_duration']) }}</p>
                    <p class="text-orange-100 text-xs mt-1">phút</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i data-feather="bar-chart-2" class="w-8 h-8"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.meetings.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Giáo viên</label>
                <select name="host_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Tất cả</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('host_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Tất cả</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Đã lên lịch</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Đang diễn ra</option>
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

            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition inline-flex items-center justify-center gap-2">
                    <i data-feather="filter" class="w-4 h-4"></i>
                    Lọc
                </button>
            </div>
        </form>
    </div>

    <!-- Video Calls List -->
    <div class="space-y-3">
        @forelse($videoCalls as $call)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow overflow-hidden">
            <div class="flex items-stretch">
                <!-- Left: Calendar Date Box -->
                <div class="flex-shrink-0 w-20 flex flex-col text-center border-r border-gray-200">
                    <div class="py-2 {{ $call->status === 'in_progress' ? 'bg-green-500' : ($call->status === 'ended' ? 'bg-gray-400' : ($call->status === 'scheduled' ? 'bg-blue-500' : 'bg-red-400')) }} text-white">
                        <div class="text-xs font-semibold uppercase">{{ $call->scheduled_at->format('M') }}</div>
                    </div>
                    <div class="flex-1 flex items-center justify-center bg-white">
                        <div class="text-2xl font-bold text-gray-900">{{ $call->scheduled_at->format('d') }}</div>
                    </div>
                </div>

                <!-- Middle: Content -->
                <div class="flex-1 p-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <!-- Title & Status -->
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-bold text-gray-900">{{ $call->title }}</h3>
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full 
                                    {{ $call->status === 'in_progress' ? 'bg-green-100 text-green-700' : 
                                       ($call->status === 'scheduled' ? 'bg-yellow-100 text-yellow-700' : 
                                       ($call->status === 'ended' ? 'bg-gray-100 text-gray-700' : 'bg-red-100 text-red-700')) }}">
                                    {{ $call->status === 'in_progress' ? '🔴 Đang họp' : 
                                       ($call->status === 'scheduled' ? '📅 Đã lên lịch' : 
                                       ($call->status === 'ended' ? '✓ Đã kết thúc' : '✗ Đã hủy')) }}
                                </span>
                            </div>
                            
                            <!-- Metadata -->
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-2">
                                <div class="flex items-center gap-1.5">
                                    <i data-feather="book-open" class="w-4 h-4 text-gray-400"></i>
                                    <span class="font-medium">{{ $call->classRoom && $call->classRoom->subject ? $call->classRoom->subject->name : 'N/A' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <i data-feather="users" class="w-4 h-4 text-gray-400"></i>
                                    <span>{{ $call->classRoom ? $call->classRoom->name : 'N/A' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <i data-feather="user" class="w-4 h-4 text-gray-400"></i>
                                    <span>{{ $call->host ? $call->host->name : 'N/A' }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <i data-feather="clock" class="w-4 h-4 text-gray-400"></i>
                                    <span>{{ $call->scheduled_at->format('H:i') }} - {{ $call->duration }} phút</span>
                                </div>
                                @if($call->room_code)
                                <div class="flex items-center gap-1.5">
                                    <i data-feather="hash" class="w-4 h-4 text-gray-400"></i>
                                    <span class="font-mono font-semibold text-blue-600">{{ $call->room_code }}</span>
                                </div>
                                @endif
                            </div>

                            @if($call->description)
                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($call->description, 100) }}</p>
                            @endif

                            @if($call->meeting_url)
                                <a href="{{ $call->meeting_url }}" target="_blank" 
                                   class="text-sm text-blue-600 hover:text-blue-800 inline-flex items-center gap-1">
                                    <i data-feather="external-link" class="w-4 h-4"></i>
                                    Link tham gia
                                </a>
                            @endif
                        </div>

                        <!-- Right: Actions -->
                        <div class="flex flex-col gap-2">
                            @if($call->status === 'scheduled')
                                <form action="{{ route('admin.meetings.updateStatus', $call->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_progress">
                                    <button type="submit" class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 inline-flex items-center gap-2 whitespace-nowrap">
                                        <i data-feather="play" class="w-4 h-4"></i>
                                        Bắt đầu
                                    </button>
                                </form>
                            @endif

                            @if($call->status === 'in_progress')
                                <form action="{{ route('admin.meetings.updateStatus', $call->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="ended">
                                    <button type="submit" class="px-4 py-2 text-sm bg-orange-600 text-white rounded-lg hover:bg-orange-700 inline-flex items-center gap-2 whitespace-nowrap">
                                        <i data-feather="square" class="w-4 h-4"></i>
                                        Kết thúc
                                    </button>
                                </form>
                            @endif

                            @if($call->status !== 'ended' && $call->status !== 'cancelled')
                                <form action="{{ route('admin.meetings.destroy', $call->id) }}" method="POST" 
                                      onsubmit="return confirm('Xác nhận hủy buổi học này?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 inline-flex items-center gap-2 whitespace-nowrap">
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                        Hủy
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
            <i data-feather="video-off" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
            <p class="text-gray-600 text-lg">Chưa có buổi học nào</p>
            <p class="text-gray-500 text-sm mt-1">Tạo buổi học mới để bắt đầu</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($videoCalls->hasPages())
    <div class="mt-6">
        {{ $videoCalls->links() }}
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

        <form action="{{ route('admin.meetings.store') }}" method="POST" class="p-6 space-y-5">
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
                    @foreach($teachers as $teacher)
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
                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500">
                        <input type="radio" name="platform" value="zoom" checked class="mr-3">
                        <div>
                            <div class="font-semibold">Zoom</div>
                            <div class="text-xs text-gray-500">Chuyên nghiệp, nhiều tính năng</div>
                        </div>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500">
                        <input type="radio" name="platform" value="jitsi" class="mr-3">
                        <div>
                            <div class="font-semibold">Jitsi</div>
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
                        class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    Tạo phòng học
                </button>
                <button type="button" 
                        onclick="document.getElementById('createModal').classList.add('hidden')"
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                    Hủy
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Initialize Feather icons
feather.replace();
</script>
@endsection
