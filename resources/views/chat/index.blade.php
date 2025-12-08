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
        
        /* Custom scrollbar for chat */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #CBD5E0;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #A0AEC0;
        }
    </style>
</head>
<body class="bg-white h-screen overflow-hidden">
    
    <div id="app" class="h-screen flex">
        
        <!-- LEFT SIDEBAR - Chat List -->
        <div class="w-80 bg-white border-r border-gray-200 flex flex-col shadow-sm">
            <!-- Header với Search -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        @auth
                            @php
                                $userRole = auth()->user()->role;
                                $homeUrl = match($userRole) {
                                    'student' => url('/student/dashboard'),
                                    'teacher' => url('/teacher/dashboard'),
                                    'admin' => url('/admin'),
                                    default => url('/dashboard')
                                };
                            @endphp
                            <a href="{{ $homeUrl }}" class="w-9 h-9 rounded-full bg-blue-100 hover:bg-blue-200 flex items-center justify-center transition" title="Về trang chủ ({{ $userRole }})">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </a>
                        @else
                            <a href="{{ url('/') }}" class="w-9 h-9 rounded-full bg-blue-100 hover:bg-blue-200 flex items-center justify-center transition" title="Về trang chủ">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </a>
                        @endauth
                        <h1 class="text-2xl font-bold text-gray-900">Đoạn chat</h1>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                            <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                            </svg>
                        </button>
                        <button onclick="showCreateRoomModal()" class="w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Search Box -->
                <div class="relative">
                    <input type="text" placeholder="Tìm kiếm chat" 
                           class="w-full bg-gray-100 rounded-full px-10 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex border-b border-gray-200 px-2">
                <button onclick="switchTab('rooms')" id="tabRooms" class="flex-1 py-3 text-sm font-semibold text-blue-600 border-b-2 border-blue-600">
                    Tất cả
                </button>
                <button onclick="switchTab('users')" id="tabUsers" class="flex-1 py-3 text-sm font-semibold text-gray-500 hover:bg-gray-50 rounded-lg">
                    Người Dùng
                </button>
            </div>

            <!-- Chat List -->
            <div id="roomsList" class="flex-1 overflow-y-auto custom-scrollbar">
                <div class="p-4 text-center text-gray-400">
                    <p class="text-sm">Đang tải phòng chat...</p>
                </div>
            </div>
            
            <!-- Users List (hidden) -->
            <div id="usersList" class="flex-1 overflow-y-auto custom-scrollbar hidden">
                <div class="p-4 text-center text-gray-400">
                    <p class="text-sm">Đang tải người dùng...</p>
                </div>
            </div>
        </div>

        <!-- CENTER - Chat Area -->
        <div class="flex-1 flex flex-col bg-white">
            <!-- Chat Header -->
            <div id="chatHeader" class="h-16 border-b border-gray-200 px-6 flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div id="roomAvatar" class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                            C
                        </div>
                        <!-- Online/Offline indicator -->
                        <span id="onlineIndicator" class="hidden absolute bottom-0 right-0 block h-3 w-3 rounded-full ring-2 ring-white"></span>
                    </div>
                    <div>
                        <h3 id="currentRoomName" class="font-semibold text-gray-900">Chọn phòng để bắt đầu trò chuyện</h3>
                        <p id="currentRoomStatus" class="text-xs text-gray-500"></p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <!-- Add Members Button (only for group chats) -->
                    <button id="addMembersButton" onclick="showAddMembersModal()" class="hidden w-9 h-9 rounded-full hover:bg-gray-100 flex items-center justify-center transition" title="Thêm thành viên">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </button>
                    <button class="w-9 h-9 rounded-full hover:bg-gray-100 flex items-center justify-center transition">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </button>
                    <button class="w-9 h-9 rounded-full hover:bg-gray-100 flex items-center justify-center transition">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                    <button onclick="toggleRightSidebar()" class="w-9 h-9 rounded-full hover:bg-gray-100 flex items-center justify-center transition">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Messages Area -->
            <div id="messagesContainer" class="flex-1 overflow-y-auto custom-scrollbar p-6 bg-white">
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <svg class="w-24 h-24 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <p class="text-lg font-medium">Chọn một đoạn chat</p>
                    <p class="text-sm mt-1">Chọn từ các cuộc trò chuyện hiện có, bắt đầu cuộc trò chuyện mới hoặc chỉ cần giữ liên lạc.</p>
                </div>
            </div>

            <!-- Input Area -->
            <div class="border-t border-gray-200 px-4 py-3 bg-white">
                <!-- File preview area -->
                <div id="filePreviewArea" class="hidden mb-3 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div id="filePreviewIcon" class="w-10 h-10 rounded bg-blue-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p id="filePreviewName" class="text-sm font-medium text-gray-900"></p>
                                <p id="filePreviewSize" class="text-xs text-gray-500"></p>
                            </div>
                        </div>
                        <button onclick="clearFilePreview()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <img id="imagePreview" class="hidden mt-2 max-h-48 rounded-lg" />
                </div>

                <div class="flex items-center space-x-2">
                    <!-- Emoji Picker Button -->
                    <div class="relative">
                        <button onclick="toggleEmojiPicker()" type="button" class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center transition">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        
                        <!-- Emoji Picker Popup -->
                        <div id="emojiPicker" class="hidden absolute bottom-12 left-0 bg-white border border-gray-200 rounded-xl shadow-2xl p-3 w-80 max-h-64 overflow-y-auto z-50">
                            <div class="grid grid-cols-8 gap-1" id="emojiGrid"></div>
                        </div>
                    </div>
                    
                    <!-- Image Upload Button -->
                    <button onclick="document.getElementById('imageInput').click()" type="button" class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center transition">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <input type="file" id="imageInput" accept="image/*" class="hidden" onchange="handleImageSelect(event)" />
                    
                    <!-- File Upload Button -->
                    <button onclick="document.getElementById('fileInput').click()" type="button" class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center transition">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                    </button>
                    <input type="file" id="fileInput" class="hidden" onchange="handleFileSelect(event)" />
                    
                    <div class="flex-1 relative">
                        <textarea 
                            id="messageInput" 
                            rows="1"
                            placeholder="Aa"
                            class="w-full bg-gray-100 rounded-full px-4 py-2 focus:outline-none resize-none max-h-32"
                            disabled
                        ></textarea>
                    </div>
                    
                    <button id="sendButton" onclick="sendMessage()" disabled
                            class="w-8 h-8 rounded-full bg-blue-600 hover:bg-blue-700 disabled:opacity-50 flex items-center justify-center transition">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDEBAR - Details (hidden by default) -->
        <div id="rightSidebar" class="w-80 bg-white border-l border-gray-200 flex-col shadow-sm hidden">
            <div class="p-6 text-center border-b border-gray-200">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-3xl font-bold mx-auto mb-3">
                    C
                </div>
                <h3 class="font-bold text-xl text-gray-900 mb-1">Chat Room Name</h3>
                <p class="text-sm text-gray-500">Hoạt động 2 giờ trước</p>
            </div>
            
            <div class="flex-1 overflow-y-auto custom-scrollbar p-4">
                <div class="space-y-4">
                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Tùy chọn trò chuyện</h4>
                        <div class="space-y-1">
                            <button class="w-full text-left px-3 py-2 hover:bg-gray-50 rounded-lg flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span class="text-sm">Tắt thông báo</span>
                            </button>
                            <button class="w-full text-left px-3 py-2 hover:bg-gray-50 rounded-lg flex items-center space-x-3">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span class="text-sm">Tìm kiếm trong cuộc trò chuyện</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <div id="selectUserModal" class="@auth hidden @endauth fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
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

    <!-- Add Members Modal -->
    <div id="addMembersModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Thêm Thành Viên</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chọn người dùng</label>
                    <div id="availableUsersList" class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg">
                        <div class="p-4 text-center text-gray-500">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto mb-2"></div>
                            <p class="text-sm">Đang tải danh sách...</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex space-x-3 mt-6">
                <button 
                    onclick="hideAddMembersModal()"
                    class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 font-medium transition-colors">
                    Hủy
                </button>
                <button 
                    id="addMembersBtn"
                    onclick="addSelectedMembers()"
                    class="flex-1 px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 font-medium shadow-md transition-all disabled:opacity-50"
                    disabled>
                    Thêm
                </button>
            </div>
        </div>
    </div>

    <script>
        // Configuration
        const API_URL = '{{ url("/api/v1/chat") }}';
        const PUSHER_KEY = '{{ env("PUSHER_APP_KEY") }}';
        const PUSHER_CLUSTER = '{{ env("PUSHER_APP_CLUSTER", "ap1") }}';
        
        // Helper function for API calls with CSRF and credentials
        async function apiCall(url, options = {}, retryCount = 0) {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            
            if (!token) {
                console.error('❌ CSRF token not found in meta tag');
                throw new Error('CSRF token missing');
            }
            
            const defaultOptions = {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(options.headers || {})
                },
                credentials: 'same-origin' // Use same-origin for web session cookies
            };
            
            const finalOptions = { ...defaultOptions, ...options, headers: { ...defaultOptions.headers, ...(options.headers || {}) } };
            
            console.log('🔍 API Call:', url);
            
            const response = await fetch(url, finalOptions);
            
            // Just log and return response, let caller handle errors
            if (!response.ok) {
                console.warn('⚠️ Response not OK:', response.status, response.statusText);
            }
            
            return response;
        }
        
        // State
        let currentRoom = null;
        let currentUser = @json(auth()->check() ? auth()->user() : ['id' => 1, 'name' => 'Guest User']);
        let echoInstance = null;
        let isAuthenticatedUser = @json(auth()->check()); // Track if user is logged in via Laravel Auth

        // ============================================
        // UTILITY FUNCTIONS (Must be defined early)
        // ============================================
        
        // Check if user is online (last login within 5 minutes)
        function isUserOnline(lastLoginAt) {
            if (!lastLoginAt) return false;
            const lastLogin = new Date(lastLoginAt);
            const now = new Date();
            const diffMinutes = (now - lastLogin) / 1000 / 60;
            return diffMinutes < 5; // Consider online if active within last 5 minutes
        }
        
        // Get time ago string (like "2 phút trước", "1 giờ trước")
        function getTimeAgo(dateString) {
            if (!dateString) return 'rất lâu trước';
            
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            const minutes = Math.floor(diff / 60000);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);
            const weeks = Math.floor(days / 7);
            const months = Math.floor(days / 30);

            if (minutes < 1) return 'vừa xong';
            if (minutes === 1) return '1 phút trước';
            if (minutes < 60) return `${minutes} phút trước`;
            if (hours === 1) return '1 giờ trước';
            if (hours < 24) return `${hours} giờ trước`;
            if (days === 1) return '1 ngày trước';
            if (days < 7) return `${days} ngày trước`;
            if (weeks === 1) return '1 tuần trước';
            if (weeks < 4) return `${weeks} tuần trước`;
            if (months === 1) return '1 tháng trước';
            if (months < 12) return `${months} tháng trước`;
            
            return date.toLocaleDateString('vi-VN');
        }
        
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

        // ============================================
        // END UTILITY FUNCTIONS
        // ============================================

        // ============================================
        // FILE & EMOJI HANDLING
        // ============================================
        
        let selectedFile = null;
        const emojis = ['😀','😃','😄','😁','😅','😂','🤣','😊','😇','🙂','🙃','😉','😌','😍','🥰','😘','😗','😙','😚','😋','😛','😝','😜','🤪','🤨','🧐','🤓','😎','🤩','🥳','😏','😒','😞','😔','😟','😕','🙁','☹️','😣','😖','😫','😩','🥺','😢','😭','😤','😠','😡','🤬','🤯','😳','🥵','🥶','😱','😨','😰','😥','😓','🤗','🤔','🤭','🤫','🤥','😶','😐','😑','😬','🙄','😯','😦','😧','😮','😲','🥱','😴','🤤','😪','😵','🤐','🥴','🤢','🤮','🤧','😷','🤒','🤕','🤑','🤠','👍','👎','👏','🙌','👐','🤝','🙏','✌️','🤞','🤟','🤘','👌','🤏','👈','👉','👆','👇','☝️','✋','🤚','🖐️','🖖','✊','🤛','🤜','❤️','🧡','💛','💚','💙','💜','🖤','🤍','🤎','💔','❣️','💕','💞','💓','💗','💖','💘','💝','💯','🔥','⭐','✨','💫','🎉','🎊','🎈'];
        
        function initializeEmojiPicker() {
            const emojiGrid = document.getElementById('emojiGrid');
            if (emojiGrid && !emojiGrid.hasChildNodes()) {
                emojiGrid.innerHTML = emojis.map(emoji => 
                    `<button type="button" onclick="insertEmoji('${emoji}')" class="text-2xl hover:bg-gray-100 rounded p-1">${emoji}</button>`
                ).join('');
            }
        }
        
        function toggleEmojiPicker() {
            const picker = document.getElementById('emojiPicker');
            if (picker.classList.contains('hidden')) {
                initializeEmojiPicker();
                picker.classList.remove('hidden');
                // Close when clicking outside
                setTimeout(() => {
                    document.addEventListener('click', closeEmojiPickerOutside);
                }, 100);
            } else {
                picker.classList.add('hidden');
                document.removeEventListener('click', closeEmojiPickerOutside);
            }
        }
        
        function closeEmojiPickerOutside(e) {
            const picker = document.getElementById('emojiPicker');
            const button = e.target.closest('button[onclick="toggleEmojiPicker()"]');
            if (!picker.contains(e.target) && !button) {
                picker.classList.add('hidden');
                document.removeEventListener('click', closeEmojiPickerOutside);
            }
        }
        
        function insertEmoji(emoji) {
            const input = document.getElementById('messageInput');
            const start = input.selectionStart;
            const end = input.selectionEnd;
            const text = input.value;
            input.value = text.substring(0, start) + emoji + text.substring(end);
            input.selectionStart = input.selectionEnd = start + emoji.length;
            input.focus();
        }
        
        function handleImageSelect(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            if (!file.type.startsWith('image/')) {
                showNotification('Vui lòng chọn file ảnh', 'error');
                return;
            }
            
            if (file.size > 10 * 1024 * 1024) { // 10MB limit
                showNotification('Ảnh không được vượt quá 10MB', 'error');
                return;
            }
            
            selectedFile = file;
            showFilePreview(file, true);
        }
        
        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            if (file.size > 50 * 1024 * 1024) { // 50MB limit
                showNotification('File không được vượt quá 50MB', 'error');
                return;
            }
            
            selectedFile = file;
            showFilePreview(file, false);
        }
        
        function showFilePreview(file, isImage) {
            const previewArea = document.getElementById('filePreviewArea');
            const fileNameEl = document.getElementById('filePreviewName');
            const fileSizeEl = document.getElementById('filePreviewSize');
            const imagePreview = document.getElementById('imagePreview');
            const fileIcon = document.getElementById('filePreviewIcon');
            
            fileNameEl.textContent = file.name;
            fileSizeEl.textContent = formatFileSize(file.size);
            
            if (isImage) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
                fileIcon.innerHTML = `
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                    </svg>
                `;
            } else {
                imagePreview.classList.add('hidden');
                fileIcon.innerHTML = `
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                `;
            }
            
            previewArea.classList.remove('hidden');
        }
        
        function clearFilePreview() {
            selectedFile = null;
            document.getElementById('filePreviewArea').classList.add('hidden');
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('imagePreview').src = '';
            document.getElementById('imageInput').value = '';
            document.getElementById('fileInput').value = '';
        }
        
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
        
        // ============================================
        // END FILE & EMOJI HANDLING
        // ============================================

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
            if (!statusEl) {
                console.log('ℹ️ Connection status element not found (OK for new design)');
                return;
            }
            
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
            const roomsList = document.getElementById('roomsList');
            
            try {
                console.log('📋 Loading rooms...');
                
                roomsList.innerHTML = '<div class="p-4 text-center text-gray-400"><p class="text-sm">Đang tải phòng chat...</p></div>';
                
                const response = await apiCall(`${API_URL}/rooms`);
                
                if (!response.ok) {
                    console.warn('⚠️ Response not OK:', response.status);
                    // Don't throw error immediately, try to parse response first
                }
                
                const data = await response.json();
                
                // Check if response indicates success
                if (!data.success) {
                    console.error('❌ API returned success=false:', data);
                    throw new Error(data.message || 'Failed to load rooms');
                }
                
                console.log('📋 Loaded', data.rooms?.length || 0, 'rooms');
                
                if (data.rooms && data.rooms.length > 0) {
                    roomsList.innerHTML = data.rooms.map(room => {
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
                        
                        // Latest message preview
                        let latestMessageText = '';
                        let latestMessageTime = '';
                        
                        if (room.latest_message) {
                            const msg = room.latest_message;
                            const senderName = msg.user ? msg.user.name : 'Unknown';
                            const isOwnMessage = msg.user && msg.user.id === currentUser.id;
                            const prefix = isOwnMessage ? 'Bạn: ' : `${senderName}: `;
                            
                            // Truncate message to 30 characters
                            const messagePreview = msg.message_text.length > 30 
                                ? msg.message_text.substring(0, 30) + '...' 
                                : msg.message_text;
                            
                            latestMessageText = `<span class="text-gray-600 truncate">${prefix}${escapeHtml(messagePreview)}</span>`;
                            latestMessageTime = `<span class="text-gray-400 text-xs whitespace-nowrap ml-1">· ${formatTime(msg.created_at)}</span>`;
                        }
                        
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
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="font-semibold text-gray-800 truncate">${escapeHtml(displayName)}</h4>
                                        ${unreadBadge}
                                    </div>
                                    ${latestMessageText ? `
                                        <div class="flex items-center text-xs mb-1">
                                            ${latestMessageText}
                                            ${latestMessageTime}
                                        </div>
                                    ` : ''}
                                    <div class="flex items-center space-x-2">
                                        ${room.room_type === 'group' ? `
                                            <span class="inline-flex items-center text-xs text-gray-500">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                                </svg>
                                                ${room.members ? room.members.length : 0} thành viên
                                            </span>
                                        ` : ''}
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${room.room_type === 'group' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'}">
                                            ${room.room_type === 'group' ? 'Nhóm' : 'Riêng tư'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `}).join('');
                } else {
                    console.log('📋 No rooms found or empty response');
                    roomsList.innerHTML = `
                        <div class="p-8 text-center text-gray-400">
                            <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-sm font-medium">Chưa có phòng chat nào</p>
                            <p class="text-xs mt-1 mb-3">Tạo phòng mới để bắt đầu!</p>
                            <button onclick="showCreateRoomModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm">
                                Tạo phòng mới
                            </button>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('❌ Error loading rooms:', error);
                console.error('❌ Error details:', error.message);
                roomsList.innerHTML = `
                    <div class="p-8 text-center text-red-400">
                        <svg class="w-16 h-16 mx-auto mb-3 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-semibold">Không thể tải danh sách phòng</p>
                        <p class="text-xs mt-1 text-gray-500">${error.message || 'Lỗi kết nối'}</p>
                        <div class="flex flex-col items-center space-y-2 mt-4">
                            <button onclick="loadRooms()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm">
                                Thử lại
                            </button>
                            @guest
                            <a href="{{ route('login') }}" class="text-xs text-blue-600 hover:underline">
                                Đăng nhập để xem phòng chat của bạn
                            </a>
                            @endguest
                        </div>
                    </div>
                `;
                showNotification('Không thể tải danh sách phòng. Vui lòng thử lại.', 'error');
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

                currentRoom = roomsData.rooms ? roomsData.rooms.find(r => r.id === roomId) : null;
                
                if (!currentRoom) {
                    showNotification('Không tìm thấy phòng', 'error');
                    return;
                }

                // Determine display name for private rooms
                let displayName = currentRoom.room_name;
                let otherUser = null;
                
                if (currentRoom.room_type === 'private' && currentRoom.members && currentRoom.members.length > 0) {
                    otherUser = currentRoom.members.find(m => m.id !== currentUser.id);
                    if (otherUser) {
                        displayName = otherUser.name;
                    }
                }

                // Update UI
                document.getElementById('currentRoomName').textContent = displayName;
                
                // Update status based on room type
                const statusElement = document.getElementById('currentRoomStatus');
                const onlineIndicator = document.getElementById('onlineIndicator');
                const addMembersBtn = document.getElementById('addMembersButton');
                
                // Update RIGHT SIDEBAR
                const rightSidebar = document.getElementById('rightSidebar');
                const rightSidebarAvatar = rightSidebar.querySelector('.w-20.h-20');
                const rightSidebarName = rightSidebar.querySelector('h3');
                const rightSidebarStatus = rightSidebar.querySelector('p.text-sm');
                
                // Update avatar letter
                if (rightSidebarAvatar) {
                    rightSidebarAvatar.textContent = displayName.charAt(0).toUpperCase();
                }
                
                // Update name
                if (rightSidebarName) {
                    rightSidebarName.textContent = displayName;
                }
                
                if (currentRoom.room_type === 'group') {
                    // Group chat: show member count
                    const memberCount = currentRoom.members_count || currentRoom.members?.length || 0;
                    statusElement.textContent = `${memberCount} thành viên`;
                    onlineIndicator.classList.add('hidden');
                    
                    // Update right sidebar for group
                    if (rightSidebarStatus) {
                        rightSidebarStatus.textContent = `${memberCount} thành viên`;
                    }
                    
                    // Show add members button for group
                    if (addMembersBtn) addMembersBtn.classList.remove('hidden');
                } else if (currentRoom.room_type === 'private' && otherUser) {
                    // Private chat: show online/offline status
                    const lastSeen = otherUser.last_login_at;
                    const isOnline = isUserOnline(lastSeen);
                    
                    if (isOnline) {
                        statusElement.textContent = 'Đang hoạt động';
                        onlineIndicator.classList.remove('hidden');
                        onlineIndicator.classList.add('bg-green-500');
                        onlineIndicator.classList.remove('bg-gray-400');
                        
                        // Update right sidebar
                        if (rightSidebarStatus) {
                            rightSidebarStatus.textContent = 'Đang hoạt động';
                        }
                    } else {
                        const timeAgo = getTimeAgo(lastSeen);
                        statusElement.textContent = `Hoạt động ${timeAgo}`;
                        onlineIndicator.classList.remove('hidden');
                        onlineIndicator.classList.add('bg-gray-400');
                        onlineIndicator.classList.remove('bg-green-500');
                        
                        // Update right sidebar
                        if (rightSidebarStatus) {
                            rightSidebarStatus.textContent = `Hoạt động ${timeAgo}`;
                        }
                    }
                    // Hide add members button for private chat
                    if (addMembersBtn) addMembersBtn.classList.add('hidden');
                } else {
                    statusElement.textContent = '';
                    onlineIndicator.classList.add('hidden');
                    if (addMembersBtn) addMembersBtn.classList.add('hidden');
                    
                    // Update right sidebar
                    if (rightSidebarStatus) {
                        rightSidebarStatus.textContent = '';
                    }
                }
                
                // Enable input
                document.getElementById('messageInput').disabled = false;
                document.getElementById('sendButton').disabled = false;
                document.getElementById('messageInput').placeholder = 'Nhập tin nhắn... (Enter để gửi, Shift+Enter để xuống dòng)';

                // Display messages
                displayMessages(messagesData.messages || []);

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
                    if (data.success && data.rooms && data.rooms.length > 0) {
                        data.rooms.forEach(room => {
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
                const response = await apiCall(`${API_URL}/rooms/${roomId}/messages`);
                const data = await response.json();
                if (data.success && data.messages) {
                    const currentMessageIds = Array.from(document.querySelectorAll('[data-message-id]'))
                        .map(el => el.dataset.messageId);
                    
                    data.messages.forEach(msg => {
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
            
            // Debug: log message data
            if (msg.message_type !== 'text' || msg.file_url) {
                console.log('📎 Rendering message with file:', {
                    type: msg.message_type,
                    file_url: msg.file_url,
                    text: msg.message_text
                });
            }
            
            // Check if message has file attachment
            let messageContent = '';
            if (msg.message_type === 'image' && msg.file_url) {
                // Check if message_text is just the filename (auto-generated) or actual caption
                const isFilenameOnly = msg.message_text && (
                    msg.message_text.match(/\.(jpg|jpeg|png|gif|webp)$/i) || 
                    msg.message_text.includes('2025-') ||
                    msg.message_text.match(/^\d{4}-\d{2}-\d{2}/)
                );
                
                const hasCaption = !isFilenameOnly && msg.message_text;
                
                messageContent = `
                    <div>
                        <img src="${msg.file_url}" alt="Image" class="w-full max-w-md rounded-lg cursor-pointer shadow-lg hover:shadow-xl transition-shadow" onclick="window.open('${msg.file_url}', '_blank')" onerror="this.onerror=null; this.src=''; this.alt='Không thể tải ảnh';" />
                    </div>
                    ${hasCaption ? `<p class="text-sm mt-2 px-4 pb-2 ${isCurrentUser && !isAI ? 'text-white' : 'text-gray-800'} whitespace-pre-wrap break-words">${escapeHtml(msg.message_text)}</p>` : ''}
                `;
            } else if (msg.message_type === 'file' && msg.file_url) {
                const fileName = msg.message_text || 'File đính kèm';
                const bgClass = isCurrentUser ? 'bg-white/20' : 'bg-gray-100';
                const hoverClass = isCurrentUser ? 'hover:bg-white/30' : 'hover:bg-gray-200';
                const iconColor = isCurrentUser ? 'text-white' : 'text-blue-600';
                messageContent = `
                    <a href="${msg.file_url}" target="_blank" download class="flex items-center space-x-2 p-3 ${bgClass} rounded-lg ${hoverClass} transition">
                        <svg class="w-8 h-8 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium ${isCurrentUser && !isAI ? 'text-white' : 'text-gray-800'}">${escapeHtml(fileName)}</p>
                            <p class="text-xs ${isCurrentUser && !isAI ? 'text-white/70' : 'text-gray-500'}">Click để tải xuống</p>
                        </div>
                    </a>
                `;
            } else {
                messageContent = `
                    <p class="text-sm ${isCurrentUser && !isAI ? 'text-white' : 'text-gray-800'} whitespace-pre-wrap break-words">
                        ${escapeHtml(msg.message_text)}
                    </p>
                `;
            }
            
            // Determine padding based on message type - no padding for images
            const bubblePadding = msg.message_type === 'image' ? 'p-0 overflow-hidden' : 'px-4 py-3';
            
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
                            <div class="${bubblePadding} rounded-2xl shadow-md ${
                                isAI ? 'bg-gradient-to-r from-purple-100 to-pink-100 border border-purple-200' :
                                isCurrentUser ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white' : 
                                'bg-white border border-gray-200'
                            }">
                                ${messageContent}
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
                message_type: msgData.message_type || 'text',
                file_url: msgData.file_url,
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

            if ((!text && !selectedFile) || !currentRoom) {
                console.log('Cannot send: missing text/file or room');
                return;
            }

            try {
                let messageText = text;
                let messageType = 'text';
                let fileUrl = null;

                // Upload file first if selected
                if (selectedFile) {
                    showNotification('Đang upload file...', 'info');
                    
                    const formData = new FormData();
                    formData.append('file', selectedFile);
                    formData.append('type', selectedFile.type.startsWith('image/') ? 'image' : 'file');
                    
                    const uploadResponse = await fetch(`${API_URL}/upload`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        credentials: 'include',
                        body: formData
                    });
                    
                    if (!uploadResponse.ok) {
                        const errorData = await uploadResponse.json();
                        showNotification(errorData.message || 'Lỗi khi upload file', 'error');
                        return;
                    }
                    
                    const uploadData = await uploadResponse.json();
                    if (uploadData.success) {
                        fileUrl = uploadData.file_url;
                        messageType = selectedFile.type.startsWith('image/') ? 'image' : 'file';
                        
                        // For images: don't send text, just send file name or empty
                        // For files: use text if provided, otherwise use filename
                        if (messageType === 'image') {
                            messageText = text || uploadData.file_name; // Keep text if user provided caption
                        } else {
                            messageText = text || uploadData.file_name;
                        }
                        
                        showNotification('Upload thành công!', 'success');
                    } else {
                        showNotification('Upload thất bại', 'error');
                        return;
                    }
                }

                console.log('Sending message to room:', currentRoom.id);
                
                const response = await apiCall(`${API_URL}/rooms/${currentRoom.id}/messages`, {
                    method: 'POST',
                    body: JSON.stringify({ 
                        message_text: messageText,
                        message_type: messageType,
                        file_url: fileUrl
                    })
                });

                console.log('Response status:', response.status);
                
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Server error response:', errorText);
                    
                    try {
                        const errorData = JSON.parse(errorText);
                        showNotification(errorData.message || 'Lỗi khi gửi tin nhắn', 'error');
                    } catch (e) {
                        showNotification(`Lỗi ${response.status}: ${response.statusText}`, 'error');
                    }
                    return;
                }

                const data = await response.json();
                console.log('Message sent successfully:', data);

                if (data.success) {
                    input.value = '';
                    input.style.height = 'auto';
                    
                    // Clear file preview
                    clearFilePreview();
                    
                    // In polling mode, add message immediately
                    if (!echoInstance && data.message) {
                        appendMessage(data.message);
                        
                        // Show AI typing indicator if room has AI
                        if (currentRoom.has_ai) {
                            showAITyping();
                        }
                    }
                } else {
                    showNotification(data.message || 'Không thể gửi tin nhắn', 'error');
                }
            } catch (error) {
                console.error('Error sending message:', error);
                showNotification('Lỗi khi gửi tin nhắn: ' + error.message, 'error');
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
                console.log('Creating room:', { roomName, includeAI });
                
                const response = await apiCall(`${API_URL}/rooms`, {
                    method: 'POST',
                    body: JSON.stringify({
                        room_name: roomName,
                        room_type: 'group',
                        include_ai: includeAI
                    })
                });

                console.log('Create room response status:', response.status);

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Server error:', errorText);
                    
                    try {
                        const errorData = JSON.parse(errorText);
                        showNotification(errorData.message || 'Không thể tạo phòng', 'error');
                        if (errorData.errors) {
                            console.error('Validation errors:', errorData.errors);
                        }
                    } catch (e) {
                        showNotification(`Lỗi ${response.status}: ${response.statusText}`, 'error');
                    }
                    return;
                }

                const data = await response.json();
                console.log('Room created:', data);

                if (data.success && data.room) {
                    showNotification(`Đã tạo phòng "${roomName}" thành công!`, 'success');
                    hideCreateRoomModal();
                    nameInput.value = '';
                    await loadRooms();
                    selectRoom(data.room.id);
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

            if (tab === 'rooms') {
                roomsTab.classList.add('text-blue-600', 'border-blue-600');
                roomsTab.classList.remove('text-gray-500');
                usersTab.classList.add('text-gray-500');
                usersTab.classList.remove('text-blue-600', 'border-blue-600');
                roomsList.classList.remove('hidden');
                usersList.classList.add('hidden');
            } else {
                usersTab.classList.add('text-blue-600', 'border-blue-600');
                usersTab.classList.remove('text-gray-500');
                roomsTab.classList.add('text-gray-500');
                roomsTab.classList.remove('text-blue-600', 'border-blue-600');
                usersList.classList.remove('hidden');
                roomsList.classList.add('hidden');
                loadUsers();
            }
        }

        // Toggle right sidebar
        function toggleRightSidebar() {
            const sidebar = document.getElementById('rightSidebar');
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                sidebar.classList.add('flex');
            } else {
                sidebar.classList.add('hidden');
                sidebar.classList.remove('flex');
            }
        }

        // Load users for private chat
        async function loadUsers() {
            try {
                const response = await apiCall(`${API_URL}/users`);
                const data = await response.json();
                
                const usersList = document.getElementById('usersList');
                
                if (data.success && data.users && data.users.length > 0) {
                    usersList.innerHTML = data.users.map(user => {
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
                
                const response = await apiCall(`${API_URL}/rooms/private`, {
                    method: 'POST',
                    body: JSON.stringify({
                        other_user_id: userId
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success && data.room) {
                    console.log('Private room created/found:', data.room);
                    
                    // Switch back to rooms tab
                    switchTab('rooms');
                    
                    // Reload rooms and select the new/existing room
                    await loadRooms();
                    
                    // Wait a bit for rooms to load, then select the room
                    setTimeout(() => {
                        selectRoom(data.room.id);
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

        // Add Members Modal functions
        let selectedMembersToAdd = [];
        
        async function showAddMembersModal() {
            if (!currentRoom || currentRoom.room_type !== 'group') {
                showNotification('Chỉ có thể thêm thành viên vào nhóm chat', 'warning');
                return;
            }
            
            document.getElementById('addMembersModal').classList.remove('hidden');
            await loadAvailableUsers();
        }

        function hideAddMembersModal() {
            document.getElementById('addMembersModal').classList.add('hidden');
            selectedMembersToAdd = [];
        }

        async function loadAvailableUsers() {
            const listContainer = document.getElementById('availableUsersList');
            
            try {
                const response = await apiCall(`${API_URL}/users`);
                const data = await response.json();
                
                if (!data.success || !data.users) {
                    throw new Error('Failed to load users');
                }
                
                // Filter out current members
                const currentMemberIds = currentRoom.members.map(m => m.id);
                const availableUsers = data.users.filter(u => !currentMemberIds.includes(u.id));
                
                if (availableUsers.length === 0) {
                    listContainer.innerHTML = `
                        <div class="p-4 text-center text-gray-500">
                            <p class="text-sm">Tất cả người dùng đã là thành viên</p>
                        </div>
                    `;
                    return;
                }
                
                listContainer.innerHTML = availableUsers.map(user => `
                    <div class="flex items-center space-x-3 p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100"
                         onclick="toggleUserSelection(${user.id}, '${escapeHtml(user.name)}')">
                        <input type="checkbox" 
                               id="user-${user.id}" 
                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                               onclick="event.stopPropagation()">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">${escapeHtml(user.name)}</p>
                            <p class="text-xs text-gray-500">${escapeHtml(user.email)}</p>
                        </div>
                    </div>
                `).join('');
                
            } catch (error) {
                console.error('Error loading users:', error);
                listContainer.innerHTML = `
                    <div class="p-4 text-center text-red-500">
                        <p class="text-sm">Không thể tải danh sách người dùng</p>
                    </div>
                `;
            }
        }

        function toggleUserSelection(userId, userName) {
            const checkbox = document.getElementById(`user-${userId}`);
            checkbox.checked = !checkbox.checked;
            
            if (checkbox.checked) {
                if (!selectedMembersToAdd.includes(userId)) {
                    selectedMembersToAdd.push(userId);
                }
            } else {
                selectedMembersToAdd = selectedMembersToAdd.filter(id => id !== userId);
            }
            
            // Enable/disable add button
            const addBtn = document.getElementById('addMembersBtn');
            addBtn.disabled = selectedMembersToAdd.length === 0;
        }

        async function addSelectedMembers() {
            if (selectedMembersToAdd.length === 0) {
                showNotification('Vui lòng chọn ít nhất một người', 'warning');
                return;
            }
            
            try {
                const addBtn = document.getElementById('addMembersBtn');
                addBtn.disabled = true;
                addBtn.textContent = 'Đang thêm...';
                
                // Add each member
                for (const userId of selectedMembersToAdd) {
                    const response = await apiCall(`${API_URL}/rooms/${currentRoom.id}/members`, {
                        method: 'POST',
                        body: JSON.stringify({ user_id: userId })
                    });
                    
                    if (!response.ok) {
                        console.error('Failed to add member:', userId);
                    }
                }
                
                showNotification(`Đã thêm ${selectedMembersToAdd.length} thành viên`, 'success');
                hideAddMembersModal();
                
                // Reload room to update member count
                await loadRooms();
                if (currentRoom) {
                    selectRoom(currentRoom.id);
                }
                
            } catch (error) {
                console.error('Error adding members:', error);
                showNotification('Lỗi khi thêm thành viên', 'error');
            } finally {
                const addBtn = document.getElementById('addMembersBtn');
                addBtn.disabled = false;
                addBtn.textContent = 'Thêm';
            }
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
            console.log('=== CHAT INITIALIZATION START ===');
            console.log('📌 User:', currentUser);
            console.log('📌 Is Authenticated:', isAuthenticatedUser);
            
            @auth
            // User is authenticated - hide modal and load chat immediately
            console.log('✅ User authenticated via Laravel, loading chat...');
            hideSelectUserModal();
            
            try {
                // Verify current user from API
                const response = await apiCall(`${API_URL}/current-user`);
                const data = await response.json();
                
                if (data.success && data.data && data.data.is_authenticated) {
                    currentUser = data.data;
                    console.log('✅ Verified user from API:', currentUser);
                } else {
                    console.warn('⚠️ API returned guest status despite Laravel auth');
                }
            } catch (error) {
                console.error('❌ Error verifying user:', error);
                // Continue with currentUser from Blade template
            }
            
            await initializeChat();
            @else
            // Not authenticated - show error message
            console.log('⚠️ User not authenticated - redirecting to login');
            const roomsList = document.getElementById('roomsList');
            roomsList.innerHTML = `
                <div class="p-8 text-center text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <p class="text-sm font-semibold mb-2">Vui lòng đăng nhập</p>
                    <p class="text-xs text-gray-400 mb-4">Bạn cần đăng nhập để sử dụng chat</p>
                    <a href="{{ route('login') }}" class="inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Đăng nhập ngay
                    </a>
                </div>
            `;
            @endauth
            
            console.log('=== CHAT INITIALIZATION END ===');
        });

        // Initialize CSRF - no action needed, token already in meta tag
        async function initializeCsrf() {
            // Function kept for compatibility
        }

        // Check if user is logged in (simplified)
        async function checkCurrentUser() {
            // This function is kept for backward compatibility but not used in new flow
            console.log('ℹ️ checkCurrentUser called');
            @auth
            await initializeChat();
            @endauth
            
            try {
                const response = await apiCall(`${API_URL}/current-user`);
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
                    hideSelectUserModal(); // Always hide modal
                    
                    // Hide change user button if authenticated via Laravel
                    const changeUserBtn = document.getElementById('changeUserBtn');
                    if (changeUserBtn) {
                        changeUserBtn.style.display = 'none'; // Always hide for security
                    }
                    
                    // Initialize chat immediately
                    await initializeChat();
                } else {
                    // No user found - show modal to select user
                    console.warn('⚠️ No current user - showing user selection');
                    showSelectUserModal();
                }
            } catch (error) {
                console.error('❌ Error checking current user:', error);
                showNotification('Không thể kết nối đến server. Vui lòng thử lại.', 'error');
                // Don't redirect, just show error
            }
        }

        // Update user info in header (only for non-auth users)
        function updateUserInfo() {
            @guest
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
            @endguest
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
                // Load all users
                const response = await apiCall('{{ url("/api/v1/chat/users") }}');
                const data = await response.json();
                
                let allUsers = [];
                
                if (data.success && data.users && data.users.length > 0) {
                    // Use real users from database
                    allUsers = data.users;
                } else {
                    // Fallback: use test users if no data
                    allUsers = [
                        { id: 1, name: 'Guest User', email: 'guest@megalearning.local' },
                        { id: 2, name: 'Admin User', email: 'admin@megalearning.local' },
                        { id: 3, name: 'Teacher Nguyen', email: 'teacher@megalearning.local' },
                        { id: 4, name: 'Student A', email: 'student1@megalearning.local' },
                        { id: 5, name: 'Student B', email: 'student2@megalearning.local' }
                    ];
                }
                
                list.innerHTML = allUsers.map(user => {
                    return `
                        <button 
                            onclick="selectUser(${user.id}, '${escapeHtml(user.name)}', '${escapeHtml(user.email)}')"
                            class="w-full p-4 bg-gray-50 hover:bg-indigo-50 rounded-xl transition-all duration-200 border-2 border-transparent hover:border-indigo-300 flex items-center space-x-4 group"
                        >
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                ${user.name.charAt(0).toUpperCase()}
                            </div>
                            <div class="flex-1 text-left">
                                <h4 class="font-semibold text-gray-800 group-hover:text-indigo-600">${escapeHtml(user.name)}</h4>
                                <p class="text-sm text-gray-500">${escapeHtml(user.email)}</p>
                            </div>
                        </button>
                    `;
                }).join('');
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
                
                const response = await apiCall(`${API_URL}/set-user`, {
                    method: 'POST',
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
            const modal = document.getElementById('selectUserModal');
            if (modal) {
                modal.classList.add('hidden');
                console.log('✅ Modal hidden');
            }
        }

        // Initialize chat after user is selected
        async function initializeChat() {
            console.log('🚀 Initializing chat...');
            hideSelectUserModal(); // Hide modal first
            initializeEcho();
            await loadRooms();
            subscribeToRoomUpdates();
            console.log('✅ Chat initialized');
        }
    </script>
</body>
</html>
