@extends('layouts.app')

@section('title', $room->room_name)

@section('content')
<div class="container mx-auto px-4 py-8 h-screen flex flex-col">
    <div class="max-w-7xl mx-auto w-full flex-1 flex flex-col bg-white rounded-lg shadow-lg overflow-hidden">
        
        <!-- Chat Header -->
        <div class="bg-blue-600 text-white px-6 py-4 flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('chat.index') }}" class="mr-4 hover:bg-blue-700 p-2 rounded">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold">{{ $room->room_name }}</h2>
                    <p class="text-sm text-blue-100">
                        {{ $room->members->count() }} members
                        @if($room->room_type === 'subject' && $room->subject)
                            • {{ $room->subject->subject_name }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                <button class="hover:bg-blue-700 p-2 rounded" title="Room Info">
                    <i class="fas fa-info-circle"></i>
                </button>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="messages-container" class="flex-1 overflow-y-auto p-6 bg-gray-50">
            @foreach($messages as $message)
                <div class="mb-4 flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-md {{ $message->user_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-white text-gray-800' }} rounded-lg shadow px-4 py-3">
                        @if($message->user_id !== auth()->id())
                            <p class="text-xs font-semibold mb-1 {{ $message->user_id === auth()->id() ? 'text-blue-100' : 'text-blue-600' }}">
                                {{ $message->user->name }}
                            </p>
                        @endif
                        
                        @if($message->message_type === 'image')
                            <img src="{{ $message->file_url }}" 
                                 alt="Image" 
                                 class="max-w-xs rounded-lg mb-2 cursor-pointer hover:opacity-90 transition" 
                                 onclick="window.open('{{ $message->file_url }}', '_blank')">
                            @if($message->message_text && $message->message_text !== basename($message->file_url))
                                <p class="text-sm mt-1">{{ $message->message_text }}</p>
                            @endif
                        @elseif($message->message_type === 'file')
                            @php
                                $ext = pathinfo($message->file_url, PATHINFO_EXTENSION);
                                $iconMap = [
                                    'pdf' => 'fas fa-file-pdf text-red-500',
                                    'doc' => 'fas fa-file-word text-blue-500',
                                    'docx' => 'fas fa-file-word text-blue-500',
                                    'xls' => 'fas fa-file-excel text-green-500',
                                    'xlsx' => 'fas fa-file-excel text-green-500',
                                    'ppt' => 'fas fa-file-powerpoint text-orange-500',
                                    'pptx' => 'fas fa-file-powerpoint text-orange-500',
                                    'zip' => 'fas fa-file-archive text-yellow-600',
                                    'rar' => 'fas fa-file-archive text-yellow-600',
                                    'txt' => 'fas fa-file-alt text-gray-500'
                                ];
                                $icon = $iconMap[$ext] ?? 'fas fa-file text-gray-500';
                            @endphp
                            <a href="{{ $message->file_url }}" 
                               target="_blank" 
                               class="flex items-center gap-2 p-3 {{ $message->user_id === auth()->id() ? 'bg-blue-700 hover:bg-blue-800' : 'bg-gray-100 hover:bg-gray-200' }} rounded-lg transition">
                                <i class="{{ $icon }} text-xl"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium truncate">{{ $message->message_text }}</p>
                                    <p class="text-xs {{ $message->user_id === auth()->id() ? 'text-blue-200' : 'text-gray-500' }}">
                                        Nhấn để tải xuống
                                    </p>
                                </div>
                                <i class="fas fa-download text-sm"></i>
                            </a>
                        @else
                            <p class="break-words">{{ $message->message_text }}</p>
                        @endif
                        
                        <p class="text-xs mt-1 {{ $message->user_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }}">
                            {{ $message->created_at->format('H:i') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Message Input -->
        <div class="bg-white border-t px-6 py-4">
            <!-- File Preview Area -->
            <div id="file-preview-area" class="mb-3 hidden">
                <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <div id="preview-content" class="flex-1"></div>
                    <button type="button" id="remove-file-btn" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-times-circle text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Upload Progress Bar -->
            <div id="upload-progress" class="mb-3 hidden">
                <div class="flex items-center gap-2 text-sm text-gray-600 mb-1">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Đang upload...</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progress-bar" class="bg-blue-600 h-2 rounded-full transition-all" style="width: 0%"></div>
                </div>
            </div>
            
            <form id="message-form" class="flex gap-2">
                @csrf
                <!-- Hidden file inputs -->
                <input type="file" id="image-input" accept="image/*" class="hidden">
                <input type="file" id="file-input" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar" class="hidden">
                <input type="hidden" id="file-url-input" name="file_url">
                <input type="hidden" id="message-type-input" name="message_type" value="text">
                
                <!-- Attach buttons -->
                <div class="flex gap-1">
                    <button 
                        type="button"
                        id="image-btn"
                        class="text-gray-500 hover:text-blue-600 px-3 py-3 rounded-lg hover:bg-gray-100 transition"
                        title="Gửi ảnh">
                        <i class="fas fa-image text-xl"></i>
                    </button>
                    <button 
                        type="button"
                        id="file-btn"
                        class="text-gray-500 hover:text-blue-600 px-3 py-3 rounded-lg hover:bg-gray-100 transition"
                        title="Gửi file">
                        <i class="fas fa-paperclip text-xl"></i>
                    </button>
                </div>
                
                <input 
                    type="text" 
                    id="message-input"
                    name="message_text"
                    placeholder="Nhập tin nhắn..." 
                    class="flex-1 border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    autocomplete="off">
                <button 
                    type="submit"
                    id="send-btn"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messages-container');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-btn');
    
    // File upload elements
    const imageBtn = document.getElementById('image-btn');
    const fileBtn = document.getElementById('file-btn');
    const imageInput = document.getElementById('image-input');
    const fileInput = document.getElementById('file-input');
    const fileUrlInput = document.getElementById('file-url-input');
    const messageTypeInput = document.getElementById('message-type-input');
    const filePreviewArea = document.getElementById('file-preview-area');
    const previewContent = document.getElementById('preview-content');
    const removeFileBtn = document.getElementById('remove-file-btn');
    const uploadProgress = document.getElementById('upload-progress');
    const progressBar = document.getElementById('progress-bar');
    
    let currentFile = null;
    let currentFileData = null;
    
    // Scroll to bottom
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
    
    // Image button click
    imageBtn.addEventListener('click', () => imageInput.click());
    fileBtn.addEventListener('click', () => fileInput.click());
    
    // Handle image selection
    imageInput.addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        // Validate image size (10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('Ảnh quá lớn! Kích thước tối đa: 10MB');
            imageInput.value = '';
            return;
        }
        
        await uploadFile(file, 'image');
    });
    
    // Handle file selection
    fileInput.addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        // Validate file size (50MB)
        if (file.size > 50 * 1024 * 1024) {
            alert('File quá lớn! Kích thước tối đa: 50MB');
            fileInput.value = '';
            return;
        }
        
        await uploadFile(file, 'file');
    });
    
    // Upload file function
    async function uploadFile(file, type) {
        try {
            // Show progress
            uploadProgress.classList.remove('hidden');
            progressBar.style.width = '0%';
            sendBtn.disabled = true;
            
            const formData = new FormData();
            formData.append('file', file);
            formData.append('type', type);
            formData.append('_token', '{{ csrf_token() }}');
            
            // Simulate progress (real progress needs XMLHttpRequest)
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += 10;
                if (progress <= 90) {
                    progressBar.style.width = progress + '%';
                }
            }, 100);
            
            const response = await fetch('{{ route("chat.upload") }}', {
                method: 'POST',
                body: formData
            });
            
            clearInterval(progressInterval);
            progressBar.style.width = '100%';
            
            const data = await response.json();
            
            if (data.success) {
                currentFileData = data;
                currentFile = file;
                
                // Show preview
                showFilePreview(file, type, data);
                
                // Set hidden inputs
                fileUrlInput.value = data.file_url;
                messageTypeInput.value = data.message_type;
                
                // Clear file inputs
                imageInput.value = '';
                fileInput.value = '';
            } else {
                alert(data.message || 'Lỗi khi upload file');
            }
            
        } catch (error) {
            console.error('Upload error:', error);
            alert('Lỗi khi upload file');
        } finally {
            uploadProgress.classList.add('hidden');
            sendBtn.disabled = false;
        }
    }
    
    // Show file preview
    function showFilePreview(file, type, data) {
        filePreviewArea.classList.remove('hidden');
        
        if (type === 'image') {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContent.innerHTML = `
                    <div class="flex items-center gap-3">
                        <img src="${e.target.result}" class="w-16 h-16 object-cover rounded">
                        <div>
                            <p class="font-medium text-sm">${file.name}</p>
                            <p class="text-xs text-gray-500">${data.file_size_formatted}</p>
                        </div>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            const icon = getFileIcon(file.name);
            previewContent.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-16 h-16 flex items-center justify-center bg-gray-200 rounded">
                        <i class="${icon} text-2xl text-gray-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-sm">${file.name}</p>
                        <p class="text-xs text-gray-500">${data.file_size_formatted}</p>
                    </div>
                </div>
            `;
        }
    }
    
    // Remove file
    removeFileBtn.addEventListener('click', function() {
        currentFile = null;
        currentFileData = null;
        filePreviewArea.classList.add('hidden');
        fileUrlInput.value = '';
        messageTypeInput.value = 'text';
        imageInput.value = '';
        fileInput.value = '';
    });
    
    // Get file icon based on extension
    function getFileIcon(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        const iconMap = {
            'pdf': 'fas fa-file-pdf',
            'doc': 'fas fa-file-word',
            'docx': 'fas fa-file-word',
            'xls': 'fas fa-file-excel',
            'xlsx': 'fas fa-file-excel',
            'ppt': 'fas fa-file-powerpoint',
            'pptx': 'fas fa-file-powerpoint',
            'zip': 'fas fa-file-archive',
            'rar': 'fas fa-file-archive',
            'txt': 'fas fa-file-alt'
        };
        return iconMap[ext] || 'fas fa-file';
    }
    
    // Handle form submission
    messageForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const messageText = messageInput.value.trim();
        const fileUrl = fileUrlInput.value;
        const messageType = messageTypeInput.value;
        
        // Check if there's content to send
        if (!messageText && !fileUrl) {
            alert('Vui lòng nhập tin nhắn hoặc chọn file');
            return;
        }
        
        try {
            sendBtn.disabled = true;
            
            const response = await fetch('{{ route("chat.sendMessage", $room->room_id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message_text: messageText || (currentFileData ? currentFileData.file_name : ''),
                    message_type: messageType,
                    file_url: fileUrl
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Clear inputs
                messageInput.value = '';
                fileUrlInput.value = '';
                messageTypeInput.value = 'text';
                
                // Clear preview
                if (currentFile) {
                    filePreviewArea.classList.add('hidden');
                    currentFile = null;
                    currentFileData = null;
                }
                
                // Add message to UI
                appendMessage(data.message);
                
                // Scroll to bottom
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            } else {
                alert(data.message || 'Lỗi khi gửi tin nhắn');
            }
        } catch (error) {
            console.error('Error sending message:', error);
            alert('Lỗi khi gửi tin nhắn');
        } finally {
            sendBtn.disabled = false;
        }
    });
    
    function appendMessage(message) {
        const isOwn = message.user_id === {{ auth()->id() }};
        const messageDiv = document.createElement('div');
        messageDiv.className = `mb-4 flex ${isOwn ? 'justify-end' : 'justify-start'}`;
        
        let content = '';
        
        if (message.message_type === 'image') {
            content = `
                <img src="${message.file_url}" alt="Image" class="max-w-xs rounded-lg mb-2 cursor-pointer" onclick="window.open('${message.file_url}', '_blank')">
                ${message.message_text ? `<p class="text-sm mt-1">${message.message_text}</p>` : ''}
            `;
        } else if (message.message_type === 'file') {
            const icon = getFileIcon(message.message_text);
            content = `
                <a href="${message.file_url}" target="_blank" class="flex items-center gap-2 p-3 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition">
                    <i class="${icon} text-xl"></i>
                    <span class="underline">${message.message_text}</span>
                </a>
            `;
        } else {
            content = `<p class="break-words">${message.message_text}</p>`;
        }
        
        messageDiv.innerHTML = `
            <div class="max-w-md ${isOwn ? 'bg-blue-600 text-white' : 'bg-white text-gray-800'} rounded-lg shadow px-4 py-3">
                ${!isOwn ? `<p class="text-xs font-semibold mb-1 ${isOwn ? 'text-blue-100' : 'text-blue-600'}">${message.user.name}</p>` : ''}
                ${content}
                <p class="text-xs mt-1 ${isOwn ? 'text-blue-100' : 'text-gray-500'}">
                    ${new Date(message.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'})}
                </p>
            </div>
        `;
        
        messagesContainer.appendChild(messageDiv);
    }
    
    // TODO: Implement real-time broadcasting with Pusher/Laravel Echo
    // Echo.private('chat-room.{{ $room->room_id }}')
    //     .listen('.message.sent', (e) => {
    //         appendMessage(e.message);
    //         messagesContainer.scrollTop = messagesContainer.scrollHeight;
    //     });
});
</script>
@endpush
