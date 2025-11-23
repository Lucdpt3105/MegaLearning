@extends('layouts.app')

@section('title', 'Quản lý Buổi học Trực tuyến')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Buổi học Trực tuyến 🎥</h1>
                <p class="text-gray-600 mt-1">UC-GV-001: Quản lý các buổi học online</p>
            </div>
            <a href="{{ route('teacher.video-calls.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-lg hover:shadow-xl transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tạo buổi học mới
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('teacher.video-calls.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lớp học</label>
                <select name="class_room_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">Tất cả lớp</option>
                    @foreach($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" {{ request('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                            {{ $classRoom->name }} - {{ $classRoom->subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
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
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Lọc
                </button>
            </div>
        </form>
    </div>

    <!-- Video Calls List -->
    <div class="grid grid-cols-1 gap-4">
        @forelse($videoCalls as $call)
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-xl font-bold text-gray-900">{{ $call->title }}</h3>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $call->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $call->status === 'in_progress' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $call->status === 'ended' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $call->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            @if($call->status === 'scheduled') 📅 Đã lên lịch
                            @elseif($call->status === 'in_progress') 🔴 Đang diễn ra
                            @elseif($call->status === 'ended') ✅ Đã kết thúc
                            @else ❌ Đã hủy
                            @endif
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 text-sm">
                        <div>
                            <p class="text-gray-600">Lớp học</p>
                            <p class="font-medium">{{ $call->classRoom->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Môn học</p>
                            <p class="font-medium">{{ $call->classRoom->subject->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Thời gian</p>
                            <p class="font-medium">{{ $call->scheduled_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Thời lượng</p>
                            <p class="font-medium">{{ $call->duration }} phút</p>
                        </div>
                    </div>

                    @if($call->description)
                    <p class="mt-3 text-gray-600">{{ Str::limit($call->description, 100) }}</p>
                    @endif

                    <div class="mt-4 flex items-center gap-2 text-sm">
                        <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-full font-mono">
                            {{ $call->room_code }}
                        </span>
                        @if($call->is_recording)
                        <span class="px-3 py-1 bg-red-50 text-red-700 rounded-full">
                            🔴 Ghi hình
                        </span>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col gap-2 ml-4">
                    @if($call->status === 'scheduled')
                        <form action="{{ route('teacher.video-calls.start', $call) }}" method="POST" class="w-full" target="_blank">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                ▶ Bắt đầu
                            </button>
                        </form>
                    @endif

                    @if($call->status === 'in_progress')
                        <a href="{{ route('teacher.video-calls.join', $call) }}" 
                           target="_blank"
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-center">
                            🎥 Tham gia
                        </a>
                        <form action="{{ route('teacher.video-calls.end', $call) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                ⏹ Kết thúc
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('teacher.video-calls.show', $call) }}" 
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-center">
                        Chi tiết
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <p class="text-gray-600 text-lg">Chưa có buổi học trực tuyến nào</p>
            <a href="{{ route('teacher.video-calls.create') }}" class="inline-block mt-4 text-blue-600 hover:text-blue-700">
                Tạo buổi học đầu tiên →
            </a>
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
@endsection
