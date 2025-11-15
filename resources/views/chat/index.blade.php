<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MegaLearning - Chat Realtime</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        .chat-message { animation: slideIn 0.3s ease-out; }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(100px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideOutRight {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(100px); }
        }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen">
    
    <div id="app" class="h-screen flex flex-col p-4">
        
        <!-- Header -->
        <div class="bg-white rounded-t-2xl shadow-lg p-6 border-b-2 border-indigo-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div id="userAvatar" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white w-12 h-12 rounded-xl flex items-center justify-center text-2xl font-bold shadow-lg">
                        ?
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">MegaLearning Chat</h1>
                        <p id="userInfo" class="text-sm text-gray-500">Đang tải...</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Change User Button - Only show if not authenticated via Laravel -->
                    <button 
                        id="changeUserBtn"
                        onclick="showSelectUserModal()"
                        class="flex items-center space-x-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl shadow-sm transition-all duration-200"
                        style="display: none;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span class="font-medium">Đổi User</span>
                    </button>
                    
                    <!-- Home Button -->
                    <a href="/" 
                       class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl shadow-md transition-all duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="font-medium">Trang chủ</span>
                    </a>
                    
                    <!-- Connection Status -->
                    <span id="connectionStatus" class="flex items-center space-x-2 text-sm">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                        </span>
                        <span class="text-gray-600">Đang kết nối...</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Chat Container -->
        <div class="flex-1 flex bg-white shadow-lg rounded-b-2xl overflow-hidden">
            
            <!-- Sidebar - Rooms List -->
            <div class="w-80 bg-gray-50 border-r border-gray-200 flex flex-col">
                <!-- Sidebar Header with Tabs -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600">
                    <div class="flex border-b border-white/20">
                        <button onclick="switchTab('rooms')" id="tabRooms" class="flex-1 text-white font-semibold text-sm py-3 px-4 border-b-2 border-white">
                            Phòng Chat
                        </button>
                        <button onclick="switchTab('users')" id="tabUsers" class="flex-1 text-white/70 font-semibold text-sm py-3 px-4 border-b-2 border-transparent hover:text-white">
                            Người Dùng
                        </button>
                    </div>
                </div>
                
                <!-- Create Room Button (only show in Rooms tab) -->
                <div id="createRoomBtn" class="p-4 border-b border-gray-200">
                    <button onclick="showCreateRoomModal()" 
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium py-3 px-4 rounded-xl shadow-md transition-all duration-200 transform hover:scale-105 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Tạo Phòng Mới</span>
                    </button>
                </div>

                <!-- Rooms List -->
                <div id="roomsList" class="flex-1 overflow-y-auto scrollbar-hide">
                    <div class="p-4 text-center text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-sm">Đang tải phòng chat...</p>
                    </div>
                </div>
                
                <!-- Users List (hidden by default) -->
                <div id="usersList" class="flex-1 overflow-y-auto scrollbar-hide hidden">
                    <div class="p-4 text-center text-gray-400">
                        <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <p class="text-sm">Đang tải người dùng...</p>
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="flex-1 flex flex-col">
                
                <!-- Chat Header -->
                <div id="chatHeader" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-4 shadow-md">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 id="currentRoomName" class="font-semibold text-lg">Chọn phòng để bắt đầu</h3>
                                <p id="currentRoomMembers" class="text-sm text-white/80"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Messages Area -->
                <div id="messagesContainer" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50 scrollbar-hide">
                    <div class="flex flex-col items-center justify-center h-full text-gray-400">
                        <svg class="w-24 h-24 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-lg font-medium">Chọn một phòng để bắt đầu trò chuyện</p>
                        <p class="text-sm mt-2">Hoặc tạo phòng mới để chat với bạn bè và AI</p>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="border-t border-gray-200 bg-white p-4">
                    <div class="flex items-end space-x-3">
                        <div class="flex-1">
                            <textarea 
                                id="messageInput" 
                                rows="1"
                                placeholder="Nhập tin nhắn... (Enter để gửi, Shift+Enter để xuống dòng)"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                                disabled
                            ></textarea>
                        </div>
                        <button 
                            id="sendButton"
                            onclick="sendMessage()" 
                            disabled
                            class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-medium px-6 py-3 rounded-xl shadow-md transition-all duration-200 transform hover:scale-105 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <span>Gửi</span>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        💡 Tip: Mention AI với từ "AI", "bot" hoặc đặt câu hỏi để nhận trợ giúp tự động!
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Select User Modal -->
    <div id="selectUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Chào mừng đến MegaLearning Chat!</h3>
            <p class="text-gray-600 mb-6">Vui lòng chọn tài khoản để bắt đầu trò chuyện</p>
            
            <div id="userSelectionList" class="space-y-2 max-h-96 overflow-y-auto scrollbar-hide">
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto mb-3"></div>
                    <p class="text-gray-500">Đang tải danh sách người dùng...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Room Modal -->
    <div id="createRoomModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Tạo Phòng Chat Mới</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tên phòng</label>
                    <input 
                        type="text" 
                        id="roomNameInput"
                        placeholder="VD: Học nhóm Toán, Chat cùng AI..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    />
                </div>

                <div class="flex items-center space-x-3 p-4 bg-indigo-50 rounded-xl">
                    <input 
                        type="checkbox" 
                        id="includeAICheckbox"
                        checked
                        class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                    />
                    <label for="includeAICheckbox" class="text-sm font-medium text-gray-700 flex items-center space-x-2">
                        <span>🤖</span>
                        <span>Thêm AI Assistant vào phòng</span>
                    </label>
                </div>
            </div>

            <div class="flex space-x-3 mt-6">
                <button 
                    onclick="hideCreateRoomModal()"
                    class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium transition-colors">
                    Hủy
                </button>
                <button 
                    onclick="createRoom()"
                    class="flex-1 px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 font-medium shadow-md transition-all">
                    Tạo Phòng
                </button>
            </div>
        </div>
    </div>

    <script>
        // Configuration
        const API_URL = '{{ url("/api/chat") }}';
        const PUSHER_KEY = '{{ env("PUSHER_APP_KEY") }}';
        const PUSHER_CLUSTER = '{{ env("PUSHER_APP_CLUSTER", "ap1") }}';
        
        // State
        let currentRoom = null;
        let currentUser = { id: 1, name: 'Guest User' };
        let echoInstance = null;
        let isAuthenticatedUser = false; // Track if user is logged in via Laravel Auth

        // Initialize Echo for Pusher
        function initializeEcho() {
            if (!PUSHER_KEY || PUSHER_KEY === '') {
                console.warn('⚠️ Pusher key chưa được cấu hình. Chạy ở chế độ polling.');
                updateConnectionStatus('polling');
                return;
            }

            try {
                window.Pusher = Pusher;
                echoInstance = new Echo({
                    broadcaster: 'pusher',
                    key: PUSHER_KEY,
                    cluster: PUSHER_CLUSTER,
                    forceTLS: true
                });

                updateConnectionStatus('connected');
                console.log('✅ Echo initialized successfully');
            } catch (error) {
                console.error('❌ Echo initialization failed:', error);
                updateConnectionStatus('error');
            }
        }

        // Update connection status indicator
        function updateConnectionStatus(status) {
            const statusEl = document.getElementById('connectionStatus');
            const statusMap = {
                connecting: { color: 'yellow', text: 'Đang kết nối...' },
                connected: { color: 'green', text: 'Đã kết nối' },
                polling: { color: 'blue', text: 'Polling mode' },
                error: { color: 'red', text: 'Lỗi kết nối' }
            };
            
            const s = statusMap[status] || statusMap.connecting;
            statusEl.innerHTML = `
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-${s.color}-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-${s.color}-500"></span>
                </span>
                <span class="text-gray-600">${s.text}</span>
            `;
        }

        // Load rooms list
        async function loadRooms() {
            try {
                console.log('📋 Loading rooms for user:', currentUser);
                
                const response = await fetch(`${API_URL}/rooms`);
                const data = await response.json();
                
                console.log('📋 Rooms response:', data);
                
                const roomsList = document.getElementById('roomsList');
                
                if (data.success && data.data.length > 0) {
                    roomsList.innerHTML = data.data.map(room => {
                        // For private rooms, show the other user's name
                        let displayName = room.room_name;
                        if (room.room_type === 'private' && room.members && room.members.length > 0) {
                            const otherUser = room.members.find(m => m.id !== currentUser.id);
                            if (otherUser) {
                                displayName = otherUser.name;
                            }
                        }
                        
                        // Badge for unread messages
                        const unreadBadge = room.unread_count > 0 ? `
                            <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 rounded-full">
                                ${room.unread_count > 99 ? '99+' : room.unread_count}
                            </span>
                        ` : '';
                        
                        return `
                        <div 
                            onclick="selectRoom(${room.id})" 
                            class="p-4 hover:bg-white cursor-pointer transition-all duration-200 border-b border-gray-100 ${currentRoom?.id === room.id ? 'bg-white border-l-4 border-l-indigo-600' : ''}"
                        >
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                    ${displayName.charAt(0).toUpperCase()}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-semibold text-gray-800 truncate">${escapeHtml(displayName)}</h4>
                                        ${unreadBadge}
                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center space-x-2">
                                        <span class="inline-flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                            </svg>
                                            ${room.members_count || 0} thành viên
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${room.room_type === 'group' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'}">
                                            ${room.room_type === 'group' ? 'Nhóm' : 'Riêng tư'}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    `}).join('');
                } else {
                    roomsList.innerHTML = `
                        <div class="p-8 text-center text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-sm">Chưa có phòng chat nào</p>
                            <p class="text-xs mt-1">Tạo phòng mới để bắt đầu!</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading rooms:', error);
                showNotification('Không thể tải danh sách phòng', 'error');
            }
        }

        // Select room and load messages
        async function selectRoom(roomId) {
            try {
                // Clear polling interval if exists
                if (window.pollingInterval) {
                    clearInterval(window.pollingInterval);
                    window.pollingInterval = null;
                }
                
                // Unsubscribe from previous room
                if (currentRoom && echoInstance) {
                    echoInstance.leaveChannel(`chat-room.${currentRoom.id}`);
                }

                // Fetch room details and messages
                const [roomResponse, messagesResponse] = await Promise.all([
                    fetch(`${API_URL}/rooms`),
                    fetch(`${API_URL}/rooms/${roomId}/messages`)
                ]);

                const roomsData = await roomResponse.json();
                const messagesData = await messagesResponse.json();

                currentRoom = roomsData.data.find(r => r.id === roomId);
                
                if (!currentRoom) {
                    showNotification('Không tìm thấy phòng', 'error');
                    return;
                }

                // Determine display name for private rooms
                let displayName = currentRoom.room_name;
                if (currentRoom.room_type === 'private' && currentRoom.members && currentRoom.members.length > 0) {
                    const otherUser = currentRoom.members.find(m => m.id !== currentUser.id);
                    if (otherUser) {
                        displayName = otherUser.name;
                    }
                }

                // Update UI
                document.getElementById('currentRoomName').textContent = displayName;
                document.getElementById('currentRoomMembers').textContent = `${currentRoom.members_count || 0} thành viên`;
                
                // Enable input
                document.getElementById('messageInput').disabled = false;
                document.getElementById('sendButton').disabled = false;
                document.getElementById('messageInput').placeholder = 'Nhập tin nhắn... (Enter để gửi, Shift+Enter để xuống dòng)';

                // Display messages
                displayMessages(messagesData.data.data || []);

                // Subscribe to realtime updates
                subscribeToRoom(roomId);

                // Refresh rooms list to highlight selected
                loadRooms();

            } catch (error) {
                console.error('Error selecting room:', error);
                showNotification('Không thể tải phòng', 'error');
            }
        }

        // Subscribe to room channel
        function subscribeToRoom(roomId) {
            if (!echoInstance) {
                console.log('📡 Polling mode - checking for new messages every 2s');
                // Clear any existing polling interval
                if (window.pollingInterval) {
                    clearInterval(window.pollingInterval);
                }
                // Poll every 2 seconds for faster AI response
                window.pollingInterval = setInterval(() => loadMessagesPolling(roomId), 2000);
                return;
            }

            echoInstance.channel(`chat-room.${roomId}`)
                .listen('.message.sent', (e) => {
                    console.log('📨 New message received:', e);
                    appendMessage(e);
                    
                    // If message is from another user and we're not in this room, update badge
                    if (e.user.id !== currentUser.id && currentRoom?.id !== roomId) {
                        updateRoomBadge(roomId, 1);
                    }
                });
        }

        // Subscribe to room updates for all rooms (for badge updates)
        function subscribeToRoomUpdates() {
            if (!echoInstance) {
                console.log('📡 Polling mode - no room update subscription');
                return;
            }

            // Subscribe to all rooms the user is in
            fetch(`${API_URL}/rooms`)
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        data.data.forEach(room => {
                            echoInstance.channel(`room-updates.${room.id}`)
                                .listen('.room.updated', (e) => {
                                    console.log('🔔 Room updated:', e);
                                    // Reload rooms to update badge counts
                                    if (e.user_id !== currentUser.id) {
                                        loadRooms();
                                    }
                                });
                        });
                    }
                });
        }

        // Polling fallback for when Pusher is not configured
        async function loadMessagesPolling(roomId) {
            if (currentRoom?.id !== roomId) return;
            
            try {
                const response = await fetch(`${API_URL}/rooms/${roomId}/messages`);
                const data = await response.json();
                if (data.success) {
                    const currentMessageIds = Array.from(document.querySelectorAll('[data-message-id]'))
                        .map(el => el.dataset.messageId);
                    
                    data.data.data.forEach(msg => {
                        // Use 'id' instead of 'message_id'
                        const msgId = msg.id || msg.message_id;
                        if (!currentMessageIds.includes(String(msgId))) {
                            appendMessage({
                                message_id: msgId,
                                id: msgId,
                                user: msg.user,
                                message_text: msg.message_text,
                                created_at: msg.created_at
                            });
                        }
                    });
                }
            } catch (error) {
                console.error('Polling error:', error);
            }
        }

        // Display messages
        function displayMessages(messages) {
            const container = document.getElementById('messagesContainer');
            
            if (messages.length === 0) {
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-gray-400">
                        <svg class="w-20 h-20 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-lg font-medium">Chưa có tin nhắn nào</p>
                        <p class="text-sm mt-2">Hãy bắt đầu cuộc trò chuyện! 💬</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = messages.map(msg => renderMessage(msg)).join('');
            scrollToBottom();
        }

        // Render single message
        function renderMessage(msg) {
            const isAI = msg.user?.email === 'ai@megalearning.local';
            // Use == for loose comparison or ensure both are same type
            const isCurrentUser = parseInt(msg.user?.id) === parseInt(currentUser.id);
            const msgId = msg.id || msg.message_id; // Support both id and message_id
            
            return `
                <div data-message-id="${msgId}" class="chat-message flex ${isCurrentUser ? 'justify-end' : 'justify-start'}">
                    <div class="flex ${isCurrentUser ? 'flex-row-reverse' : 'flex-row'} items-end space-x-2 max-w-2xl">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full ${isAI ? 'bg-gradient-to-r from-purple-500 to-pink-500' : isCurrentUser ? 'bg-gradient-to-r from-indigo-500 to-blue-500' : 'bg-gray-400'} flex items-center justify-center text-white text-sm font-medium shadow-md">
                            ${isAI ? '🤖' : (msg.user?.name?.charAt(0) || 'U')}
                        </div>
                        <div class="${isCurrentUser ? 'ml-2' : 'mr-2'}">
                            <div class="flex items-baseline ${isCurrentUser ? 'flex-row-reverse' : 'flex-row'} space-x-2 mb-1">
                                <span class="text-xs font-semibold ${isAI ? 'text-purple-600' : 'text-gray-700'}">
                                    ${escapeHtml(msg.user?.name || 'Unknown')}
                                </span>
                                <span class="text-xs text-gray-400">
                                    ${formatTime(msg.created_at)}
                                </span>
                            </div>
                            <div class="px-4 py-3 rounded-2xl shadow-md ${
                                isAI ? 'bg-gradient-to-r from-purple-100 to-pink-100 border border-purple-200' :
                                isCurrentUser ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white' : 
                                'bg-white border border-gray-200'
                            }">
                                <p class="text-sm ${isCurrentUser && !isAI ? 'text-white' : 'text-gray-800'} whitespace-pre-wrap break-words">
                                    ${escapeHtml(msg.message_text)}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Append new message
        function appendMessage(msgData) {
            const container = document.getElementById('messagesContainer');
            
            // Remove empty state if exists
            const emptyState = container.querySelector('.flex.flex-col.items-center');
            if (emptyState) {
                container.innerHTML = '';
            }

            // Use id or message_id
            const msgId = msgData.id || msgData.message_id;
            
            // Check if message already exists
            if (container.querySelector(`[data-message-id="${msgId}"]`)) {
                console.log('Message already exists:', msgId);
                return;
            }

            // Hide AI typing indicator if this is an AI message
            const isAI = msgData.user?.email === 'ai@megalearning.local';
            if (isAI) {
                hideAITyping();
            }

            const msg = {
                id: msgId,
                message_id: msgId,
                user: msgData.user,
                message_text: msgData.message_text,
                created_at: msgData.created_at
            };

            console.log('📩 Appending message:', msg);
            container.insertAdjacentHTML('beforeend', renderMessage(msg));
            scrollToBottom();
        }

        // Send message
        async function sendMessage() {
            const input = document.getElementById('messageInput');
            const text = input.value.trim();

            if (!text || !currentRoom) return;

            try {
                const response = await fetch(`${API_URL}/rooms/${currentRoom.id}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message_text: text })
                });

                const data = await response.json();

                if (data.success) {
                    input.value = '';
                    input.style.height = 'auto';
                    
                    // In polling mode, add message immediately
                    if (!echoInstance) {
                        appendMessage(data.data);
                        
                        // Show AI typing indicator if room has AI
                        if (currentRoom.has_ai) {
                            showAITyping();
                        }
                    }
                } else {
                    showNotification('Không thể gửi tin nhắn', 'error');
                }
            } catch (error) {
                console.error('Error sending message:', error);
                showNotification('Lỗi khi gửi tin nhắn', 'error');
            }
        }

        // Show AI typing indicator
        function showAITyping() {
            const container = document.getElementById('messagesContainer');
            const typingId = 'ai-typing-indicator';
            
            // Don't add if already exists
            if (container.querySelector(`#${typingId}`)) return;
            
            const typingHTML = `
                <div id="${typingId}" class="chat-message flex justify-start">
                    <div class="flex items-end space-x-2 max-w-2xl">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white text-sm font-medium shadow-md">
                            🤖
                        </div>
                        <div class="mr-2">
                            <div class="px-4 py-3 rounded-2xl shadow-md bg-gradient-to-r from-purple-100 to-pink-100 border border-purple-200">
                                <div class="flex space-x-1">
                                    <div class="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                                    <div class="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                    <div class="w-2 h-2 bg-purple-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', typingHTML);
            scrollToBottom();
        }

        // Hide AI typing indicator
        function hideAITyping() {
            const indicator = document.getElementById('ai-typing-indicator');
            if (indicator) {
                indicator.remove();
            }
        }

        // Create room
        async function createRoom() {
            const nameInput = document.getElementById('roomNameInput');
            const includeAI = document.getElementById('includeAICheckbox').checked;
            const roomName = nameInput.value.trim();

            if (!roomName) {
                showNotification('Vui lòng nhập tên phòng', 'warning');
                return;
            }

            try {
                const response = await fetch(`${API_URL}/rooms`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        room_name: roomName,
                        room_type: 'group',
                        include_ai: includeAI
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    showNotification(`Đã tạo phòng "${roomName}" thành công!`, 'success');
                    hideCreateRoomModal();
                    nameInput.value = '';
                    await loadRooms();
                    selectRoom(data.data.id);
                } else {
                    console.error('Create room failed:', data);
                    showNotification(data.message || 'Không thể tạo phòng', 'error');
                }
            } catch (error) {
                console.error('Error creating room:', error);
                showNotification('Lỗi khi tạo phòng: ' + error.message, 'error');
            }
        }

        // Tab switching
        function switchTab(tab) {
            const roomsTab = document.getElementById('tabRooms');
            const usersTab = document.getElementById('tabUsers');
            const roomsList = document.getElementById('roomsList');
            const usersList = document.getElementById('usersList');
            const createRoomBtn = document.getElementById('createRoomBtn');

            if (tab === 'rooms') {
                roomsTab.classList.add('border-white', 'text-white');
                roomsTab.classList.remove('border-transparent', 'text-white/70');
                usersTab.classList.add('border-transparent', 'text-white/70');
                usersTab.classList.remove('border-white', 'text-white');
                roomsList.classList.remove('hidden');
                usersList.classList.add('hidden');
                createRoomBtn.classList.remove('hidden');
            } else {
                usersTab.classList.add('border-white', 'text-white');
                usersTab.classList.remove('border-transparent', 'text-white/70');
                roomsTab.classList.add('border-transparent', 'text-white/70');
                roomsTab.classList.remove('border-white', 'text-white');
                usersList.classList.remove('hidden');
                roomsList.classList.add('hidden');
                createRoomBtn.classList.add('hidden');
                loadUsers();
            }
        }

        // Load users for private chat
        async function loadUsers() {
            try {
                const response = await fetch(`${API_URL}/users`);
                const data = await response.json();
                
                const usersList = document.getElementById('usersList');
                
                if (data.success && data.data.length > 0) {
                    usersList.innerHTML = data.data.map(user => {
                        const roleColors = {
                            'admin': 'bg-red-100 text-red-800',
                            'teacher': 'bg-blue-100 text-blue-800',
                            'student': 'bg-green-100 text-green-800',
                            'ai': 'bg-purple-100 text-purple-800'
                        };
                        const roleNames = {
                            'admin': 'Admin',
                            'teacher': 'Giáo viên',
                            'student': 'Học sinh',
                            'ai': 'AI Assistant'
                        };
                        
                        return `
                        <div 
                            onclick="startPrivateChat(${user.id}, ${JSON.stringify(user.name).replace(/"/g, '&quot;')})" 
                            class="p-4 hover:bg-white cursor-pointer transition-all duration-200 border-b border-gray-100"
                        >
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                    ${user.name.charAt(0).toUpperCase()}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-800 truncate">${escapeHtml(user.name)}</h4>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs ${roleColors[user.role] || 'bg-gray-100 text-gray-800'} px-2 py-0.5 rounded-full font-medium">
                                            ${roleNames[user.role] || user.role}
                                        </span>
                                        ${user.role === 'ai' ? '<span class="text-xs">🤖</span>' : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                        `;
                    }).join('');
                } else {
                    usersList.innerHTML = `
                        <div class="p-8 text-center text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <p class="text-sm">Không có người dùng</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading users:', error);
                showNotification('Không thể tải danh sách người dùng', 'error');
            }
        }

        // Start private chat with a user
        async function startPrivateChat(userId, userName) {
            try {
                // Don't show notification immediately, show loading state instead
                console.log(`Starting private chat with user ${userId} (${userName})`);
                
                const response = await fetch(`${API_URL}/rooms/private`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        other_user_id: userId
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success && data.data && data.data.id) {
                    console.log('Private room created/found:', data.data);
                    
                    // Switch back to rooms tab
                    switchTab('rooms');
                    
                    // Reload rooms and select the new/existing room
                    await loadRooms();
                    
                    // Wait a bit for rooms to load, then select the room
                    setTimeout(() => {
                        selectRoom(data.data.id);
                        showNotification(data.message || `Đã mở phòng chat với ${userName}`, 'success');
                    }, 400);
                } else {
                    console.error('Failed to create private room:', data);
                    showNotification(data.message || 'Không thể tạo phòng chat', 'error');
                }
            } catch (error) {
                console.error('Error starting private chat:', error);
                showNotification('Lỗi khi tạo phòng chat: ' + error.message, 'error');
            }
        }

        // Modal functions
        function showCreateRoomModal() {
            document.getElementById('createRoomModal').classList.remove('hidden');
        }

        function hideCreateRoomModal() {
            document.getElementById('createRoomModal').classList.add('hidden');
        }

        // Utility functions
        function escapeHtml(unsafe) {
            if (!unsafe) return '';
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function formatTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            const minutes = Math.floor(diff / 60000);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);

            if (minutes < 1) return 'Vừa xong';
            if (minutes < 60) return `${minutes} phút trước`;
            if (hours < 24) return `${hours} giờ trước`;
            if (days < 7) return `${days} ngày trước`;
            
            return date.toLocaleDateString('vi-VN');
        }

        function scrollToBottom() {
            const container = document.getElementById('messagesContainer');
            setTimeout(() => {
                container.scrollTop = container.scrollHeight;
            }, 100);
        }

        function showNotification(message, type = 'info') {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };

            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-xl shadow-lg z-50 transition-all duration-300 transform translate-x-0`;
            notification.style.animation = 'slideInRight 0.3s ease-out';
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease-in';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Input auto-resize
        document.getElementById('messageInput')?.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        // Enter to send (Shift+Enter for new line)
        document.getElementById('messageInput')?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', async function() {
            // Check if user is already selected
            await checkCurrentUser();
        });

        // Check if user is logged in
        async function checkCurrentUser() {
            try {
                const response = await fetch(`${API_URL}/current-user`);
                const data = await response.json();
                
                console.log('🔍 Checking current user:', data);
                
                if (data.success && data.data) {
                    currentUser = data.data;
                    console.log('✅ Current user:', currentUser);
                    
                    // Check if this is from Laravel Auth
                    isAuthenticatedUser = data.data.is_authenticated || false;
                    
                    console.log('🔐 Is authenticated via Laravel Auth:', isAuthenticatedUser);
                    console.log('📌 User source:', data.data.source);
                    
                    updateUserInfo(); // Update UI with user info
                    
                    if (isAuthenticatedUser) {
                        // User logged in via Laravel Auth - hide modal and change user button
                        hideSelectUserModal();
                        const changeUserBtn = document.getElementById('changeUserBtn');
                        if (changeUserBtn) changeUserBtn.style.display = 'none';
                    } else {
                        // Manual selection mode - show change user button
                        const changeUserBtn = document.getElementById('changeUserBtn');
                        if (changeUserBtn) changeUserBtn.style.display = 'flex';
                        
                        // If no user selected yet, show modal
                        if (!currentUser || currentUser.id === 1) {
                            await showSelectUserModal();
                            return; // Don't initialize chat yet
                        } else {
                            hideSelectUserModal();
                        }
                    }
                    
                    await initializeChat();
                } else {
                    // No user found - show selection modal
                    await showSelectUserModal();
                }
            } catch (error) {
                console.error('Error checking current user:', error);
                await showSelectUserModal();
            }
        }

        // Update user info in header
        function updateUserInfo() {
            if (currentUser) {
                const avatarEl = document.getElementById('userAvatar');
                const infoEl = document.getElementById('userInfo');
                
                if (avatarEl) {
                    avatarEl.textContent = currentUser.name.charAt(0).toUpperCase();
                }
                
                if (infoEl) {
                    infoEl.textContent = `Đăng nhập với: ${currentUser.name}`;
                }
                
                console.log('📝 Updated user info in header:', currentUser.name);
            }
        }

        // Show user selection modal
        async function showSelectUserModal() {
            // Don't show modal if user is authenticated via Laravel Auth
            if (isAuthenticatedUser) {
                console.log('⚠️ User is authenticated via Laravel Auth - cannot change user');
                return;
            }
            
            const modal = document.getElementById('selectUserModal');
            const list = document.getElementById('userSelectionList');
            modal.classList.remove('hidden');
            
            try {
                // Load all users (including current user if not set)
                const response = await fetch('{{ url("/api/chat/users") }}');
                const data = await response.json();
                
                if (data.success) {
                    // Add all users including those that might be excluded
                    const allUsersResponse = await fetch('{{ url("/api") }}/users');
                    let allUsers = [];
                    
                    // Fallback: use test users
                    allUsers = [
                        { id: 1, name: 'Guest User', email: 'guest@megalearning.local', role: 'guest' },
                        { id: 2, name: 'Admin User', email: 'admin@megalearning.local', role: 'admin' },
                        { id: 3, name: 'Teacher Nguyen', email: 'teacher@megalearning.local', role: 'teacher' },
                        { id: 4, name: 'Student A', email: 'student1@megalearning.local', role: 'student' },
                        { id: 5, name: 'Student B', email: 'student2@megalearning.local', role: 'student' }
                    ];
                    
                    // Try to get real users from database
                    try {
                        const usersResp = await fetch('{{ url("/api/chat/users") }}');
                        const usersData = await usersResp.json();
                        if (usersData.success && usersData.data.length > 0) {
                            // Add guest user to the list
                            allUsers = [
                                { id: 1, name: 'Guest User', email: 'guest@megalearning.local', role: 'guest' },
                                ...usersData.data
                            ];
                        }
                    } catch (e) {
                        console.warn('Could not fetch users, using fallback list');
                    }
                    
                    list.innerHTML = allUsers.map(user => {
                        const roleColors = {
                            admin: 'bg-red-100 text-red-800',
                            teacher: 'bg-blue-100 text-blue-800',
                            student: 'bg-green-100 text-green-800',
                            guest: 'bg-gray-100 text-gray-800',
                            ai: 'bg-purple-100 text-purple-800'
                        };
                        const roleIcons = {
                            admin: '👑',
                            teacher: '👨‍🏫',
                            student: '👨‍🎓',
                            guest: '👤',
                            ai: '🤖'
                        };
                        
                        return `
                            <button 
                                onclick="selectUser(${user.id}, '${escapeHtml(user.name)}', '${escapeHtml(user.email)}')"
                                class="w-full p-4 bg-gray-50 hover:bg-indigo-50 rounded-xl transition-all duration-200 border-2 border-transparent hover:border-indigo-300 flex items-center space-x-4 group"
                            >
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                    ${roleIcons[user.role] || user.name.charAt(0).toUpperCase()}
                                </div>
                                <div class="flex-1 text-left">
                                    <h4 class="font-semibold text-gray-800 group-hover:text-indigo-600">${escapeHtml(user.name)}</h4>
                                    <p class="text-sm text-gray-500">${escapeHtml(user.email)}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-medium ${roleColors[user.role] || 'bg-gray-100 text-gray-800'}">
                                    ${user.role || 'user'}
                                </span>
                            </button>
                        `;
                    }).join('');
                } else {
                    throw new Error('Could not load users');
                }
            } catch (error) {
                console.error('Error loading users:', error);
                list.innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <p>❌ Không thể tải danh sách người dùng</p>
                        <p class="text-sm mt-2">Vui lòng thử lại sau</p>
                    </div>
                `;
            }
        }

        // Select user and set session
        async function selectUser(userId, userName, userEmail) {
            try {
                // Parse userId to integer to ensure consistent comparison
                userId = parseInt(userId);
                
                console.log('👤 Selecting user:', { userId, userName, userEmail });
                
                const response = await fetch(`${API_URL}/set-user`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ user_id: userId })
                });

                const data = await response.json();
                
                console.log('👤 Set user response:', data);

                if (data.success) {
                    currentUser = { id: userId, name: userName, email: userEmail };
                    console.log('✅ User selected:', currentUser);
                    updateUserInfo(); // Update UI with user info
                    hideSelectUserModal();
                    showNotification(`Đăng nhập thành công với tài khoản ${userName}`, 'success');
                    await initializeChat();
                } else {
                    showNotification('Không thể chọn user', 'error');
                }
            } catch (error) {
                console.error('Error selecting user:', error);
                showNotification('Lỗi khi chọn user', 'error');
            }
        }

        // Hide user selection modal
        function hideSelectUserModal() {
            document.getElementById('selectUserModal').classList.add('hidden');
        }

        // Initialize chat after user is selected
        async function initializeChat() {
            initializeEcho();
            await loadRooms();
            subscribeToRoomUpdates();
        }
    </script>
</body>
</html>
