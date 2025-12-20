<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $videoCall->title }} - Họp Online</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900">
    <div class="h-screen flex flex-col">
        <!-- Header -->
        <div class="bg-gray-800 px-6 py-4 flex items-center justify-between border-b border-gray-700">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                    <span class="text-white font-semibold">{{ $videoCall->title }}</span>
                </div>
                <span class="text-gray-400 text-sm">{{ $videoCall->classRoom ? $videoCall->classRoom->name : 'N/A' }}</span>
            </div>
            
            <div class="flex items-center space-x-2">
                <span class="text-gray-400 text-sm" id="meeting-duration">00:00</span>
                <span class="text-gray-600">|</span>
                <span class="text-gray-400 text-sm">Mã: {{ $videoCall->room_code }}</span>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex overflow-hidden">
            <!-- Video Area -->
            <div class="flex-1 flex flex-col bg-gray-900 p-4">
                <!-- Main Video -->
                <div class="flex-1 relative bg-black rounded-lg overflow-hidden mb-4">
                    @if($videoCall->meeting_url)
                        <!-- Embed external meeting -->
                        <iframe 
                            src="{{ $videoCall->meeting_url }}" 
                            class="w-full h-full"
                            allow="camera; microphone; fullscreen; display-capture"
                            allowfullscreen>
                        </iframe>
                    @else
                        <!-- Placeholder for video integration -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-32 h-32 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-full flex items-center justify-center mb-4 mx-auto">
                                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-white text-xl font-semibold mb-2">{{ $videoCall->host ? $videoCall->host->name : 'Host' }}</h3>
                                <p class="text-gray-400">Đang trình chiếu...</p>
                            </div>
                        </div>
                        
                        <!-- Participant Grid -->
                        <div class="absolute bottom-4 right-4 flex flex-col space-y-2">
                            <div class="w-40 h-28 bg-gray-800 rounded-lg overflow-hidden border-2 border-blue-500 relative">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                    </div>
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-2">
                                    <p class="text-white text-xs font-medium truncate">Bạn</p>
                                </div>
                            </div>
                            
                            @foreach($participants as $participant)
                                @if($participant->id !== auth()->id())
                                <div class="w-40 h-28 bg-gray-800 rounded-lg overflow-hidden border border-gray-600 relative">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-bold text-lg">{{ strtoupper(substr($participant->name, 0, 1)) }}</span>
                                        </div>
                                    </div>
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-2">
                                        <p class="text-white text-xs font-medium truncate">{{ $participant->name }}</p>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Controls -->
                <div class="bg-gray-800 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-gray-400 text-sm">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                {{ count($participants) }} người tham gia
                            </span>
                        </div>

                        <div class="flex items-center space-x-3">
                            <!-- Microphone -->
                            <button id="toggle-mic" class="w-12 h-12 bg-gray-700 hover:bg-gray-600 rounded-full flex items-center justify-center transition-colors">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                </svg>
                            </button>

                            <!-- Camera -->
                            <button id="toggle-camera" class="w-12 h-12 bg-gray-700 hover:bg-gray-600 rounded-full flex items-center justify-center transition-colors">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>

                            <!-- Screen Share -->
                            <button id="share-screen" class="w-12 h-12 bg-gray-700 hover:bg-gray-600 rounded-full flex items-center justify-center transition-colors">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </button>

                            <!-- Chat -->
                            <button id="toggle-chat" class="w-12 h-12 bg-gray-700 hover:bg-gray-600 rounded-full flex items-center justify-center transition-colors relative">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span id="chat-badge" class="hidden absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full text-white text-xs flex items-center justify-center">0</span>
                            </button>

                            <!-- Leave -->
                            <form action="{{ route('student.video-calls.leave', $videoCall->id) }}" method="POST" id="leave-form" class="inline">
                                @csrf
                                <button type="button" onclick="confirmLeave()" class="w-12 h-12 bg-red-600 hover:bg-red-700 rounded-full flex items-center justify-center transition-colors">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <div class="text-gray-400 text-sm">
                            <span id="connection-status" class="flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                Kết nối tốt
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Sidebar -->
            <div id="chat-sidebar" class="hidden w-80 bg-gray-800 border-l border-gray-700 flex flex-col">
                <div class="p-4 border-b border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-white font-semibold">Trò chuyện</h3>
                        <button id="close-chat" class="text-gray-400 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3">
                    <!-- Messages will be inserted here -->
                    <div class="text-center text-gray-500 text-sm py-4">
                        Chưa có tin nhắn nào
                    </div>
                </div>

                <div class="p-4 border-t border-gray-700">
                    <div class="flex items-center space-x-2">
                        <input 
                            type="text" 
                            id="chat-input" 
                            placeholder="Nhập tin nhắn..." 
                            class="flex-1 bg-gray-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <button id="send-message" class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-4 py-2 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let micEnabled = true;
        let cameraEnabled = true;
        let chatOpen = false;
        let startTime = new Date();

        // Update meeting duration
        setInterval(() => {
            const now = new Date();
            const diff = Math.floor((now - startTime) / 1000);
            const minutes = Math.floor(diff / 60);
            const seconds = diff % 60;
            document.getElementById('meeting-duration').textContent = 
                `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }, 1000);

        // Toggle microphone
        document.getElementById('toggle-mic').addEventListener('click', function() {
            micEnabled = !micEnabled;
            this.classList.toggle('bg-red-600', !micEnabled);
            this.classList.toggle('bg-gray-700', micEnabled);
            
            if (!micEnabled) {
                this.innerHTML = `<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" clip-rule="evenodd"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                </svg>`;
            } else {
                this.innerHTML = `<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                </svg>`;
            }
        });

        // Toggle camera
        document.getElementById('toggle-camera').addEventListener('click', function() {
            cameraEnabled = !cameraEnabled;
            this.classList.toggle('bg-red-600', !cameraEnabled);
            this.classList.toggle('bg-gray-700', cameraEnabled);
            
            if (!cameraEnabled) {
                this.innerHTML = `<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"/>
                </svg>`;
            } else {
                this.innerHTML = `<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>`;
            }
        });

        // Screen share
        document.getElementById('share-screen').addEventListener('click', function() {
            alert('Tính năng chia sẻ màn hình sẽ được tích hợp với dịch vụ video call của bạn');
        });

        // Toggle chat
        document.getElementById('toggle-chat').addEventListener('click', function() {
            chatOpen = !chatOpen;
            document.getElementById('chat-sidebar').classList.toggle('hidden', !chatOpen);
            this.classList.toggle('bg-blue-600', chatOpen);
            this.classList.toggle('bg-gray-700', !chatOpen);
            
            if (chatOpen) {
                document.getElementById('chat-badge').classList.add('hidden');
                document.getElementById('chat-input').focus();
            }
        });

        document.getElementById('close-chat').addEventListener('click', function() {
            chatOpen = false;
            document.getElementById('chat-sidebar').classList.add('hidden');
            document.getElementById('toggle-chat').classList.remove('bg-blue-600');
            document.getElementById('toggle-chat').classList.add('bg-gray-700');
        });

        // Send message
        function sendChatMessage() {
            const input = document.getElementById('chat-input');
            const message = input.value.trim();
            
            if (message) {
                const messagesDiv = document.getElementById('chat-messages');
                const messageEl = document.createElement('div');
                messageEl.className = 'bg-blue-600 rounded-lg p-3 max-w-xs ml-auto';
                messageEl.innerHTML = `
                    <p class="text-white text-sm">${message}</p>
                    <p class="text-blue-200 text-xs mt-1">${new Date().toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })}</p>
                `;
                
                if (messagesDiv.querySelector('.text-center')) {
                    messagesDiv.innerHTML = '';
                }
                
                messagesDiv.appendChild(messageEl);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
                input.value = '';
            }
        }

        document.getElementById('send-message').addEventListener('click', sendChatMessage);
        document.getElementById('chat-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendChatMessage();
            }
        });

        // Confirm leave
        function confirmLeave() {
            if (confirm('Bạn có chắc chắn muốn rời khỏi cuộc họp?')) {
                document.getElementById('leave-form').submit();
            }
        }

        // Prevent accidental page close
        window.addEventListener('beforeunload', function(e) {
            e.preventDefault();
            e.returnValue = '';
        });
    </script>
</body>
</html>
