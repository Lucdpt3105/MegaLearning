@extends('layouts.app')

@section('title', 'Chat Rooms')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Chat Rooms</h1>
            <button 
                onclick="openCreateRoomModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-plus mr-2"></i> New Room
            </button>
        </div>

        <!-- Rooms List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($rooms as $room)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xl">
                                {{ substr($room->room_name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <h3 class="font-semibold text-lg text-gray-800">{{ $room->room_name }}</h3>
                                <span class="text-xs text-gray-500 capitalize">
                                    <i class="fas fa-tag mr-1"></i>{{ $room->room_type }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Latest Message -->
                    @if($room->latestMessage)
                        <div class="text-sm text-gray-600 mb-3 border-t pt-3">
                            <p class="truncate">
                                <strong>{{ $room->latestMessage->user->name }}:</strong>
                                {{ $room->latestMessage->message_text }}
                            </p>
                            <span class="text-xs text-gray-400">{{ $room->latestMessage->time_ago }}</span>
                        </div>
                    @endif

                    <!-- Members Count -->
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <span>
                            <i class="fas fa-users mr-1"></i>
                            {{ $room->members->count() }} members
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <a 
                            href="{{ route('chat.show', $room->room_id) }}" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded transition">
                            Open Chat
                        </a>
                        <form action="{{ route('chat.leave', $room->room_id) }}" method="POST" class="inline">
                            @csrf
                            <button 
                                type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition"
                                onclick="return confirm('Are you sure you want to leave this room?')">
                                Leave
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-comments text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500 text-lg">No chat rooms yet. Create one to get started!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Create Room Modal (implement with Alpine.js or vanilla JS) -->
@endsection

@push('scripts')
<script>
function openCreateRoomModal() {
    // Implement modal logic here
    alert('Create Room Modal - To be implemented');
}
</script>
@endpush
