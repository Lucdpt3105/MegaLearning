@extends('layouts.app')

@section('title', 'Phòng học: ' . $videoCall->title)

@section('content')
<div class="h-screen bg-gray-900 flex flex-col">
    <!-- Top Bar -->
    <div class="bg-gray-800 text-white p-4 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <h1 class="text-xl font-semibold">{{ $videoCall->title }}</h1>
            <span class="px-3 py-1 bg-red-500 rounded-full text-sm font-medium animate-pulse">
                🔴 LIVE
            </span>
        </div>
        
        <div class="flex items-center gap-3">
            @if($isHost && $videoCall->is_recording)
            <button id="toggleRecording"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg font-medium flex items-center gap-2">
                <span class="w-3 h-3 bg-white rounded-full animate-pulse"></span>
                Đang ghi hình
            </button>
            @endif
            
            <span class="text-gray-300">Mã phòng: <span class="font-mono bg-gray-700 px-2 py-1 rounded">{{ $videoCall->room_code }}</span></span>
            
            <a href="{{ route('teacher.video-calls.show', $videoCall) }}"
               class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg">
                ← Quay lại
            </a>
        </div>
    </div>

    <!-- Jitsi Meet Container -->
    <div id="jitsi-container" class="flex-1"></div>

    <!-- Bottom Controls (if needed) -->
    <div class="bg-gray-800 p-4 text-white text-center text-sm">
        <p>💡 Sử dụng các nút điều khiển trong video để tắt/bật mic, camera, chia sẻ màn hình...</p>
    </div>
</div>

<script src="https://meet.jit.si/external_api.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const domain = '{{ config("services.jitsi.domain", "meet.jit.si") }}';
    const roomName = '{{ $videoCall->room_code }}';
    const displayName = '{{ $displayName }}';
    const isHost = {{ $isHost ? 'true' : 'false' }};
    
    const options = {
        roomName: roomName,
        width: '100%',
        height: '100%',
        parentNode: document.getElementById('jitsi-container'),
        userInfo: {
            displayName: displayName,
            email: '{{ Auth::user()->email }}'
        },
        configOverwrite: {
            startWithAudioMuted: false,
            startWithVideoMuted: false,
            enableWelcomePage: false,
            prejoinPageEnabled: false,
            disableDeepLinking: true,
            // Disable lobby/waiting room - students can join directly
            enableLobbyChat: false,
            lobbyEnabled: false,
            // Recording settings
            recordingService: {
                enabled: {{ $videoCall->is_recording ? 'true' : 'false' }},
                sharingEnabled: isHost
            },
            // Chat
            disableInviteFunctions: !isHost,
            // Security - but allow everyone to join
            requireDisplayName: true,
            enableClosePage: false,
            // Make everyone able to unmute (disable moderator requirement)
            disableModeratorIndicator: false,
            startSilent: false
        },
        interfaceConfigOverwrite: {
            TOOLBAR_BUTTONS: [
                'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
                'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
                'videoquality', 'filmstrip', 'invite', 'feedback', 'stats', 'shortcuts',
                'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone',
            ],
            SETTINGS_SECTIONS: ['devices', 'language', 'moderator', 'profile', 'calendar'],
            SHOW_JITSI_WATERMARK: false,
            SHOW_WATERMARK_FOR_GUESTS: false,
            DEFAULT_REMOTE_DISPLAY_NAME: 'Học sinh',
            MOBILE_APP_PROMO: false
        },
        onload: function() {
            console.log('Jitsi Meet loaded successfully');
        }
    };

    const api = new JitsiMeetExternalAPI(domain, options);

    // Event listeners
    api.addListener('videoConferenceJoined', function(event) {
        console.log('Joined conference:', event);
        
        // Track participation
        fetch('{{ route("teacher.video-calls.join", $videoCall) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                user_id: {{ Auth::id() }},
                joined_at: new Date().toISOString()
            })
        });
    });

    api.addListener('videoConferenceLeft', function(event) {
        console.log('Left conference:', event);
        
        @if($isHost)
        // Redirect host back to show page
        window.location.href = '{{ route("teacher.video-calls.show", $videoCall) }}';
        @else
        // Redirect students
        window.location.href = '/student/dashboard';
        @endif
    });

    api.addListener('recordingStatusChanged', function(event) {
        console.log('Recording status:', event);
        
        if (event.on) {
            document.getElementById('toggleRecording')?.classList.add('animate-pulse');
        } else {
            document.getElementById('toggleRecording')?.classList.remove('animate-pulse');
        }
    });

    // Recording controls for host
    @if($isHost && $videoCall->is_recording)
    document.getElementById('toggleRecording')?.addEventListener('click', function() {
        api.executeCommand('toggleRecording', {
            mode: 'file',
            dropboxToken: null // Or configure Dropbox integration
        });
    });
    @endif

    // Prevent accidental close
    window.addEventListener('beforeunload', function(e) {
        if (api) {
            e.preventDefault();
            e.returnValue = 'Bạn có chắc muốn rời khỏi buổi học?';
        }
    });
});
</script>

<style>
#jitsi-container {
    overflow: hidden;
}
</style>
@endsection
