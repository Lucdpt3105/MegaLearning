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
                        
                        @if($message->message_type === 'text')
                            <p class="break-words">{{ $message->message_text }}</p>
                        @elseif($message->message_type === 'image')
                            <img src="{{ $message->file_url }}" alt="Image" class="max-w-full rounded">
                            <p class="mt-2 text-sm">{{ $message->message_text }}</p>
                        @elseif($message->message_type === 'file')
                            <a href="{{ $message->file_url }}" class="underline" target="_blank">
                                <i class="fas fa-file mr-1"></i> {{ $message->message_text }}
                            </a>
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
            <form id="message-form" class="flex gap-3">
                @csrf
                <input 
                    type="text" 
                    id="message-input"
                    name="message_text"
                    placeholder="Type a message..." 
                    class="flex-1 border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    autocomplete="off"
                    required>
                <button 
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition">
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
    
    // Scroll to bottom
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
    
    // Handle form submission
    messageForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const messageText = messageInput.value.trim();
        if (!messageText) return;
        
        try {
            const response = await fetch('{{ route("chat.sendMessage", $room->room_id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    message_text: messageText,
                    message_type: 'text'
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Clear input
                messageInput.value = '';
                
                // Add message to UI (real-time broadcasting will handle this later)
                appendMessage(data.message);
                
                // Scroll to bottom
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        } catch (error) {
            console.error('Error sending message:', error);
            alert('Failed to send message');
        }
    });
    
    function appendMessage(message) {
        const isOwn = message.user_id === {{ auth()->id() }};
        const messageDiv = document.createElement('div');
        messageDiv.className = `mb-4 flex ${isOwn ? 'justify-end' : 'justify-start'}`;
        
        messageDiv.innerHTML = `
            <div class="max-w-md ${isOwn ? 'bg-blue-600 text-white' : 'bg-white text-gray-800'} rounded-lg shadow px-4 py-3">
                ${!isOwn ? `<p class="text-xs font-semibold mb-1 text-blue-600">${message.user.name}</p>` : ''}
                <p class="break-words">${message.message_text}</p>
                <p class="text-xs mt-1 ${isOwn ? 'text-blue-100' : 'text-gray-500'}">
                    ${new Date(message.created_at).toLocaleTimeString('en-US', {hour: '2-digit', minute: '2-digit'})}
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
