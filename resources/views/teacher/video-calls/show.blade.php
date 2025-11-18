@extends('layouts.app')

@section('title', $videoCall->title)

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('teacher.video-calls.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900">{{ $videoCall->title }}</h1>
                <p class="text-gray-600 mt-1">{{ $videoCall->classRoom->name }} - {{ $videoCall->classRoom->subject->name }}</p>
            </div>
            <span class="px-4 py-2 rounded-full text-sm font-semibold
                {{ $videoCall->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                {{ $videoCall->status === 'in_progress' ? 'bg-green-100 text-green-800' : '' }}
                {{ $videoCall->status === 'ended' ? 'bg-gray-100 text-gray-800' : '' }}">
                @if($videoCall->status === 'scheduled') 📅 Đã lên lịch
                @elseif($videoCall->status === 'in_progress') 🔴 Đang diễn ra
                @elseif($videoCall->status === 'ended') ✅ Đã kết thúc
                @else ❌ Đã hủy
                @endif
            </span>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="flex gap-3 mb-6">
        @if($videoCall->status === 'scheduled')
            <form action="{{ route('teacher.video-calls.start', $videoCall) }}" method="POST" class="inline" target="_blank">
                @csrf
                <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Bắt đầu buổi học
                </button>
            </form>
        @endif

        @if($videoCall->status === 'in_progress')
            <a href="{{ route('teacher.video-calls.join', $videoCall) }}" 
               target="_blank"
               class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Tham gia phòng
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
            <form action="{{ route('teacher.video-calls.end', $videoCall) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                    ⏹ Kết thúc
                </button>
            </form>
        @endif

        @if($videoCall->status === 'scheduled')
            <a href="{{ route('teacher.video-calls.edit', $videoCall) }}" 
               class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">
                ✏️ Chỉnh sửa
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Details Card -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Thông tin buổi học</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Nền tảng</p>
                        <div class="flex items-center gap-2">
                            @if(($videoCall->settings['platform'] ?? 'jitsi') === 'zoom')
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                    🎥 Zoom
                                </span>
                            @else
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                    🎥 Jitsi Meet
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Ghi hình</p>
                        <p class="font-medium">{{ $videoCall->is_recording ? '🔴 Bật' : '⚪ Tắt' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Thời gian bắt đầu</p>
                        <p class="font-medium">{{ $videoCall->scheduled_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Thời lượng dự kiến</p>
                        <p class="font-medium">{{ $videoCall->duration }} phút</p>
                    </div>
                    @if($videoCall->started_at)
                    <div>
                        <p class="text-gray-600 text-sm">Bắt đầu thực tế</p>
                        <p class="font-medium">{{ $videoCall->started_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                    @if($videoCall->ended_at)
                    <div>
                        <p class="text-gray-600 text-sm">Kết thúc</p>
                        <p class="font-medium">{{ $videoCall->ended_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>

                @if($videoCall->description)
                <div class="mt-4 pt-4 border-t">
                    <p class="text-gray-600 text-sm mb-2">Mô tả</p>
                    <p class="text-gray-900">{{ $videoCall->description }}</p>
                </div>
                @endif
            </div>

            <!-- Room Code Card -->
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg shadow-sm p-6 border-2 border-purple-200">
                <h3 class="font-semibold text-gray-900 mb-3">Mã phòng & Link tham gia</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-600">Mã phòng</label>
                        <div class="flex items-center gap-2 mt-1">
                            <input type="text" readonly value="{{ $videoCall->room_code }}"
                                   class="flex-1 px-4 py-2 bg-white border border-gray-300 rounded-lg font-mono text-lg">
                            <button onclick="copyToClipboard('{{ $videoCall->room_code }}')"
                                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                                📋 Sao chép
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Link tham gia</label>
                        <div class="flex items-center gap-2 mt-1">
                            <input type="text" readonly value="{{ $videoCall->meeting_url }}"
                                   class="flex-1 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm">
                            <button onclick="copyToClipboard('{{ $videoCall->meeting_url }}')"
                                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                                📋
                            </button>
                        </div>
                    </div>

                    @if(($videoCall->settings['platform'] ?? 'jitsi') === 'zoom' && isset($videoCall->settings['zoom_password']))
                    <div>
                        <label class="text-sm text-gray-600">Mật khẩu Zoom</label>
                        <div class="flex items-center gap-2 mt-1">
                            <input type="text" readonly value="{{ $videoCall->settings['zoom_password'] }}"
                                   class="flex-1 px-4 py-2 bg-white border border-gray-300 rounded-lg font-mono">
                            <button onclick="copyToClipboard('{{ $videoCall->settings['zoom_password'] }}')"
                                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                                📋
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recording -->
            @if($videoCall->recording_url)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-3">🎬 Video ghi hình</h3>
                <a href="{{ $videoCall->recording_url }}" target="_blank"
                   class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700">
                    Xem video
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            </div>
            @endif
        </div>

        <!-- Students List -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-4">
                    Học sinh ({{ $videoCall->classRoom->students->count() }})
                </h3>

                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @foreach($videoCall->classRoom->students as $student)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $student->name }}</p>
                                <p class="text-xs text-gray-600">{{ $student->email }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <button onclick="openInviteModal()"
                        class="mt-4 w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                    📧 Gửi lời mời
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Invite Modal -->
<div id="inviteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-semibold text-gray-900 mb-4">Mời học sinh tham gia</h3>
        
        <form action="{{ route('teacher.video-calls.invite', $videoCall) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Chọn học sinh</label>
                <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-300 rounded-lg p-3">
                    @foreach($videoCall->classRoom->students as $student)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                               class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm">{{ $student->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Lời nhắn (tùy chọn)</label>
                <textarea name="message" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                          placeholder="Nhắc nhở học sinh chuẩn bị..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeInviteModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Hủy
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Gửi lời mời
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Đã sao chép vào clipboard!');
    });
}

function openInviteModal() {
    document.getElementById('inviteModal').classList.remove('hidden');
    document.getElementById('inviteModal').classList.add('flex');
}

function closeInviteModal() {
    document.getElementById('inviteModal').classList.add('hidden');
    document.getElementById('inviteModal').classList.remove('flex');
}
</script>
@endsection
