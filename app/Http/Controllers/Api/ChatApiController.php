<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Services\AIService;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatApiController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Get all chat rooms (public or for authenticated user)
     */
    public function getRooms(Request $request)
    {
        // Get current user (guest user ID 1 for now)
        $userId = 1;
        
        // Get all group rooms and private rooms for the current user
        $rooms = ChatRoom::where('is_active', true)
            ->where(function($query) use ($userId) {
                $query->where('room_type', 'group')
                      ->orWhere(function($q) use ($userId) {
                          $q->where('room_type', 'private')
                            ->whereHas('members', function($m) use ($userId) {
                                $m->where('user_id', $userId);
                            });
                      });
            })
            ->with(['latestMessage.user', 'members'])
            ->withCount('members')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rooms
        ]);
    }

    /**
     * Get messages for a specific room
     */
    public function getMessages(Request $request, $roomId)
    {
        $room = ChatRoom::findOrFail($roomId);

        // Allow access to public group rooms
        // No authentication check for public rooms

        $messages = ChatMessage::where('room_id', $roomId)
            ->with('user:id,name,email')
            ->active()
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request, $roomId)
    {
        $validated = $request->validate([
            'message_text' => 'required|string|max:5000',
            'message_type' => 'in:text,image,file',
            'file_url' => 'nullable|string|max:500'
        ]);

        $room = ChatRoom::findOrFail($roomId);
        
        // Use user ID 1 (guest) if not authenticated
        $userId = 1;

        // No authentication check - allow public access to group rooms

        $message = ChatMessage::create([
            'room_id' => $roomId,
            'user_id' => $userId,
            'message_text' => $validated['message_text'],
            'message_type' => $validated['message_type'] ?? 'text',
            'file_url' => $validated['file_url'] ?? null,
        ]);

        // Update room timestamp
        $room->touch();

        // Load user relationship
        $message->load('user:id,name,email');

        // Broadcast event
        broadcast(new MessageSent($message));

        // Trigger AI response if AI is a member (run immediately, not after response)
        if ($this->aiService->isConfigured()) {
            $aiUser = $this->aiService->getAIUser();
            if ($aiUser && $room->members->contains($aiUser->id)) {
                // Run AI response asynchronously
                $this->handleAIResponse($room, $message);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message
        ], 201);
    }

    /**
     * Handle AI auto-response
     */
    protected function handleAIResponse(ChatRoom $room, ChatMessage $userMessage)
    {
        try {
            // Don't respond to AI's own messages
            $aiUser = $this->aiService->getAIUser();
            if (!$aiUser || $userMessage->user_id === $aiUser->id) {
                return;
            }

            \Log::info('AI processing message', [
                'room_id' => $room->id,
                'message' => substr($userMessage->message_text, 0, 50)
            ]);

            $aiResponse = $this->aiService->generateResponse($room, $userMessage);

            if ($aiResponse) {
                \Log::info('AI generated response', [
                    'response' => substr($aiResponse, 0, 100)
                ]);

                $aiMessage = ChatMessage::create([
                    'room_id' => $room->id,
                    'user_id' => $aiUser->id,
                    'message_text' => $aiResponse,
                    'message_type' => 'text',
                ]);

                $aiMessage->load('user:id,name,email');
                
                \Log::info('AI message created', [
                    'message_id' => $aiMessage->id
                ]);

                // Broadcast to everyone
                broadcast(new MessageSent($aiMessage));
                
                \Log::info('AI message broadcasted');
            } else {
                \Log::info('AI decided not to respond');
            }
        } catch (\Exception $e) {
            \Log::error('AI Response Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'room_id' => $room->id
            ]);
        }
    }

    /**
     * Create new chat room
     */
    public function createRoom(Request $request)
    {
        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            'room_type' => 'required|in:group,private,subject',
            'subject_id' => 'nullable|exists:subjects,subject_id',
            'members' => 'array',
            'members.*' => 'exists:users,id',
            'include_ai' => 'boolean'
        ]);

        // Use user ID 1 (guest) if not authenticated
        $creatorId = 1;

        $room = ChatRoom::create([
            'room_name' => $validated['room_name'],
            'room_type' => $validated['room_type'],
            'subject_id' => $validated['subject_id'] ?? null,
            'created_by' => $creatorId,
            'is_active' => true
        ]);

        // Add other members (optional)
        if (isset($validated['members'])) {
            foreach ($validated['members'] as $memberId) {
                $room->members()->attach($memberId, [
                    'role' => 'member',
                    'joined_at' => now()
                ]);
            }
        }

        // Add AI bot if requested
        if (($validated['include_ai'] ?? false) && $this->aiService->isConfigured()) {
            $aiUser = $this->aiService->getAIUser();
            if ($aiUser && !$room->members->contains($aiUser->id)) {
                $room->members()->attach($aiUser->id, [
                    'role' => 'bot',
                    'joined_at' => now()
                ]);
            }
        }

        $room->load(['members']);

        return response()->json([
            'success' => true,
            'message' => 'Chat room created successfully',
            'data' => [
                'id' => $room->id,
                'room_name' => $room->room_name,
                'room_type' => $room->room_type,
                'subject_id' => $room->subject_id,
                'created_by' => $room->created_by,
                'is_active' => $room->is_active,
                'members' => $room->members,
                'members_count' => $room->members->count(),
                'created_at' => $room->created_at,
                'updated_at' => $room->updated_at
            ]
        ], 201);
    }

    /**
     * Join a room
     */
    public function joinRoom(Request $request, $roomId)
    {
        $room = ChatRoom::findOrFail($roomId);
        
        // Simplified - allow anyone to join (use guest user ID 1)
        return response()->json([
            'success' => true,
            'message' => 'Public room - no need to join'
        ]);
    }

    /**
     * Leave a room
     */
    public function leaveRoom(Request $request, $roomId)
    {
        // Simplified - no-op for public rooms
        return response()->json([
            'success' => true,
            'message' => 'Left room successfully'
        ]);
    }

    /**
     * Delete message
     */
    public function deleteMessage(Request $request, $messageId)
    {
        $message = ChatMessage::findOrFail($messageId);
        
        // Simplified - allow deletion (no ownership check)
        $message->update(['is_deleted' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully'
        ]);
    }

    /**
     * Get all users for private chat
     */
    public function getUsers(Request $request)
    {
        $users = \App\Models\User::select('id', 'name', 'email')
            ->where('id', '!=', 1) // Exclude guest user
            ->orderBy('name')
            ->get()
            ->map(function($user) {
                // Determine role based on email or add role column if exists
                if (str_contains($user->email, 'admin')) {
                    $user->role = 'admin';
                } elseif (str_contains($user->email, 'teacher')) {
                    $user->role = 'teacher';
                } elseif (str_contains($user->email, 'ai@')) {
                    $user->role = 'ai';
                } else {
                    $user->role = 'student';
                }
                return $user;
            });

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Create or get private chat room between two users
     */
    public function createPrivateRoom(Request $request)
    {
        $validated = $request->validate([
            'other_user_id' => 'required|exists:users,id'
        ]);

        $userId = 1; // Current user (guest for now)
        $otherUserId = $validated['other_user_id'];

        // Check if private room already exists between these two users
        $existingRoom = ChatRoom::where('room_type', 'private')
            ->whereHas('members', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->whereHas('members', function($q) use ($otherUserId) {
                $q->where('user_id', $otherUserId);
            })
            ->first();

        if ($existingRoom) {
            return response()->json([
                'success' => true,
                'message' => 'Phòng chat đã tồn tại',
                'data' => [
                    'id' => $existingRoom->id,
                    'room_id' => $existingRoom->id, // Backward compatibility
                    'room_name' => $existingRoom->room_name,
                    'room_type' => $existingRoom->room_type,
                    'members' => $existingRoom->members
                ]
            ]);
        }

        // Create new private room
        $otherUser = \App\Models\User::findOrFail($otherUserId);
        
        $room = ChatRoom::create([
            'room_name' => "Chat với {$otherUser->name}",
            'room_type' => 'private',
            'created_by' => $userId,
            'is_active' => true
        ]);

        // Add both users to the room
        $room->members()->attach($userId, [
            'role' => 'member',
            'joined_at' => now()
        ]);
        
        $room->members()->attach($otherUserId, [
            'role' => 'member',
            'joined_at' => now()
        ]);

        $room->load('members');

        return response()->json([
            'success' => true,
            'message' => 'Đã tạo phòng chat mới',
            'data' => [
                'id' => $room->id,
                'room_id' => $room->id, // Backward compatibility
                'room_name' => $room->room_name,
                'room_type' => $room->room_type,
                'members' => $room->members
            ]
        ], 201);
    }
}

