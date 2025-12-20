<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $recipientIds;

    /**
     * Create a new event instance.
     *
     * @param ChatMessage $message
     * @param array $recipientIds Array of user IDs who should receive this notification
     */
    public function __construct(ChatMessage $message, array $recipientIds)
    {
        $this->message = $message;
        $this->recipientIds = $recipientIds;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [];
        
        // Broadcast to each recipient's private channel
        foreach ($this->recipientIds as $userId) {
            $channels[] = new PrivateChannel("chat.user.{$userId}");
        }
        
        return $channels;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'NewChatMessage';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'message_id' => $this->message->message_id,
            'room_id' => $this->message->room_id,
            'user_id' => $this->message->user_id,
            'message_text' => $this->message->message_text,
            'created_at' => $this->message->created_at->toISOString(),
            'user' => [
                'id' => $this->message->user->id,
                'name' => $this->message->user->name,
                'avatar' => $this->message->user->avatar,
            ],
        ];
    }
}
