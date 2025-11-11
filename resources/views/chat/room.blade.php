<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $room['name'] ?? 'Chat Room' }} - MegaLearning</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-database-compat.js"></script>
</head>
<body class="bg-gray-100 h-screen flex flex-col">
    <div class="bg-white shadow-sm px-4 py-3 flex items-center justify-between">
        <div>
            <a href="{{ route('chat.index') }}" class="text-blue-500 hover:text-blue-600">← Back</a>
            <h1 class="text-xl font-bold inline-block ml-4">{{ $room['name'] ?? 'Chat Room' }}</h1>
        </div>
        <div class="text-sm text-gray-600">
            <span class="font-medium">{{ auth()->user()->name }}</span>
        </div>
    </div>

    <!-- Messages Container -->
    <div id="messages" class="flex-1 overflow-y-auto px-4 py-4 space-y-3">
        <!-- Messages will be loaded here -->
    </div>

    <!-- Message Input -->
    <div class="bg-white border-t px-4 py-3">
        <form id="messageForm" class="flex gap-2">
            @csrf
            <input 
                type="text" 
                id="messageInput" 
                name="message" 
                placeholder="Type a message..." 
                required
                class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                Send
            </button>
        </form>
    </div>

    <script>
        const roomId = '{{ $roomId }}';
        const currentUserId = {{ auth()->id() }};
        const currentUserName = '{{ auth()->user()->name }}';
        
        // Firebase Configuration
        const firebaseConfig = {
            apiKey: "AIzaSyBqVP8RHxfXqJx5X5JQXJQXJQXJQXJQXJQ", // Replace with your config
            databaseURL: "https://megalearning-default-rtdb.firebaseio.com",
            projectId: "megalearning"
        };
        
        firebase.initializeApp(firebaseConfig);
        const database = firebase.database();
        const messagesRef = database.ref(`chats/${roomId}/messages`);

        // Display message in UI
        function displayMessage(messageId, messageData) {
            const messagesContainer = document.getElementById('messages');
            const existingMessage = document.getElementById(`msg-${messageId}`);
            
            if (existingMessage) return; // Don't duplicate

            const isOwnMessage = messageData.user_id == currentUserId;
            const messageDiv = document.createElement('div');
            messageDiv.id = `msg-${messageId}`;
            messageDiv.className = `flex ${isOwnMessage ? 'justify-end' : 'justify-start'}`;
            
            const time = new Date(messageData.created_at).toLocaleTimeString('vi-VN', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });

            messageDiv.innerHTML = `
                <div class="${isOwnMessage ? 'bg-blue-500 text-white' : 'bg-white'} px-4 py-2 rounded-lg max-w-md shadow">
                    ${!isOwnMessage ? `<p class="text-xs font-bold text-gray-700 mb-1">${messageData.user_name}</p>` : ''}
                    <p>${escapeHtml(messageData.message)}</p>
                    <p class="text-xs ${isOwnMessage ? 'text-blue-100' : 'text-gray-500'} mt-1">${time}</p>
                </div>
            `;
            
            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Load initial messages
        messagesRef.orderByChild('timestamp').limitToLast(50).once('value', (snapshot) => {
            snapshot.forEach((childSnapshot) => {
                displayMessage(childSnapshot.key, childSnapshot.val());
            });
        });

        // Listen for new messages (real-time)
        messagesRef.orderByChild('timestamp').limitToLast(1).on('child_added', (snapshot) => {
            const messageData = snapshot.val();
            if (messageData.timestamp > Date.now() / 1000 - 5) { // Only new messages
                displayMessage(snapshot.key, messageData);
            }
        });

        // Send message
        document.getElementById('messageForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            
            if (!message) return;

            try {
                const response = await fetch(`{{ route('chat.send', $roomId) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message })
                });

                if (response.ok) {
                    input.value = '';
                }
            } catch (error) {
                console.error('Error sending message:', error);
                alert('Failed to send message');
            }
        });

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>
