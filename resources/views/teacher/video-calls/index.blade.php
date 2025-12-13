@extends('layouts.app')

@section('title', 'Quản lý Buổi học Trực tuyến')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Buổi học Trực tuyến 🎥</h1>
                <p class="text-gray-600 mt-1">Quản lý các buổi học online</p>
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
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 mb-6">
        <form method="GET" action="{{ route('teacher.video-calls.index') }}" class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Lớp học</label>
                <select name="class_room_id" class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tất cả lớp</option>
                    @foreach($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" {{ request('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                            {{ $classRoom->name }} - {{ $classRoom->subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[180px]">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Trạng thái</label>
                <select name="status" class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tất cả</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Đã lên lịch</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Đang diễn ra</option>
                    <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Đã kết thúc</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>

            <div class="flex-1 min-w-[160px]">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Từ ngày</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <button type="submit" class="h-10 px-5 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Lọc
            </button>
        </form>
    </div>

    <!-- Video Calls List (Card-based) -->
    <div class="space-y-3">
        @forelse($videoCalls as $call)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow overflow-hidden">
            <div class="flex items-stretch">
                <!-- Left: Calendar Date Box -->
                <div class="flex-shrink-0 w-20 flex flex-col text-center border-r border-gray-200">
                    <div class="py-2 {{ $call->status === 'in_progress' ? 'bg-green-500' : ($call->status === 'ended' ? 'bg-gray-400' : 'bg-blue-500') }} text-white">
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
                            <!-- Title -->
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $call->title }}</h3>
                            
                            <!-- Metadata -->
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-2">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <span class="font-medium">{{ $call->classRoom->subject->name }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>{{ $call->scheduled_at->format('H:i') }} • {{ $call->duration }} phút</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span>{{ $call->classRoom->name }}</span>
                                </div>
                            </div>

                            <!-- Description (if exists) -->
                            @if($call->description)
                            <p class="text-sm text-gray-600 line-clamp-1 mb-2">{{ $call->description }}</p>
                            @endif

                            <!-- Tags -->
                            <div class="flex items-center gap-2 text-xs">
                                @if($call->is_recording)
                                <span class="px-2 py-1 bg-red-50 text-red-700 rounded-full font-medium">
                                    🔴 Ghi hình
                                </span>
                                @endif
                                <span class="px-2 py-1 bg-purple-50 text-purple-700 rounded-full font-medium">
                                    Zoom
                                </span>
                            </div>
                        </div>

                        <!-- Right: Status & Actions -->
                        <div class="flex flex-col items-end gap-3">
                            <!-- Status Badge -->
                            <span class="px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap
                                {{ $call->status === 'scheduled' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $call->status === 'in_progress' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $call->status === 'ended' ? 'bg-gray-100 text-gray-700' : '' }}
                                {{ $call->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                @if($call->status === 'scheduled') 📅 Đã lên lịch
                                @elseif($call->status === 'in_progress') 🔴 Đang diễn ra
                                @elseif($call->status === 'ended') ✅ Đã kết thúc
                                @else ❌ Đã hủy
                                @endif
                            </span>

                            <!-- Action Buttons -->
                            <div class="flex flex-col gap-2 w-28">
                                @if($call->status === 'scheduled')
                                    <form action="{{ route('teacher.video-calls.start', $call) }}" method="POST" target="_blank">
                                        @csrf
                                        <button type="submit" class="w-full h-9 px-3 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                                            ▶ Bắt đầu
                                        </button>
                                    </form>
                                @endif

                                @if($call->status === 'in_progress')
                                    <a href="{{ route('teacher.video-calls.join', $call) }}" 
                                       target="_blank"
                                       class="h-9 px-3 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors inline-flex items-center justify-center">
                                        🎥 Tham gia
                                    </a>
                                    <form action="{{ route('teacher.video-calls.end', $call) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full h-9 px-3 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors">
                                            ⏹ Kết thúc
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('teacher.video-calls.show', $call) }}" 
                                   class="h-9 px-3 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors inline-flex items-center justify-center">
                                    Chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
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
