<?php

namespace App\Events;

use App\Models\ChatRoom;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $roomId;
    public $unreadCount;
    public $lastMessage;

    /**
     * Create a new event instance.
     */
    public function __construct($roomId, $unreadCount = 0, $lastMessage = null)
    {
        $this->roomId = $roomId;
        $this->unreadCount = $unreadCount;
        $this->lastMessage = $lastMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('room-updates.' . $this->roomId),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'room.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'room_id' => $this->roomId,
            'unread_count' => $this->unreadCount,
            'last_message' => $this->lastMessage,
        ];
    }
}
