<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - MegaLearning</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">💬 Chat Rooms</h1>
        
        <!-- Create Room Button -->
        <button onclick="showCreateRoomModal()" class="mb-4 bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
            ➕ Create New Room
        </button>

        <!-- Rooms List -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if($rooms)
                @foreach($rooms as $roomId => $room)
                    <a href="{{ route('chat.room', $roomId) }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                        <h3 class="text-xl font-bold mb-2">{{ $room['name'] ?? 'Unnamed Room' }}</h3>
                        <p class="text-gray-600 text-sm">Type: {{ ucfirst($room['type'] ?? 'group') }}</p>
                        <p class="text-gray-500 text-xs mt-2">Created: {{ $room['created_at'] ?? 'N/A' }}</p>
                    </a>
                @endforeach
            @else
                <p class="col-span-3 text-center text-gray-500 py-8">No chat rooms yet. Create one!</p>
            @endif
        </div>
    </div>

    <!-- Create Room Modal (Simple) -->
    <div id="createRoomModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg w-96">
            <h2 class="text-2xl font-bold mb-4">Create Chat Room</h2>
            <form id="createRoomForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Room Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Type</label>
                    <select name="type" class="w-full px-4 py-2 border rounded-lg">
                        <option value="group">Group</option>
                        <option value="class">Class</option>
                        <option value="private">Private</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg">Create</button>
                    <button type="button" onclick="hideCreateRoomModal()" class="flex-1 bg-gray-300 px-4 py-2 rounded-lg">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showCreateRoomModal() {
            document.getElementById('createRoomModal').classList.remove('hidden');
        }

        function hideCreateRoomModal() {
            document.getElementById('createRoomModal').classList.add('hidden');
        }

        document.getElementById('createRoomForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            
            try {
                const response = await fetch('{{ route("chat.create") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': formData.get('_token')
                    },
                    body: JSON.stringify({
                        name: formData.get('name'),
                        type: formData.get('type')
                    })
                });

                if (response.ok) {
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to create room');
            }
        });
    </script>
</body>
</html>
