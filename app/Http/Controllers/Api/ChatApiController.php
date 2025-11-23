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
     * Get current user ID (from Auth or session)
     */
    protected function getCurrentUserId()
    {
        // Priority 1: Check if user is authenticated via Laravel Auth
        if (Auth::check()) {
            // Clear any manual session selection when using Auth
            session()->forget('chat_user_id');
            return Auth::id();
        }
        
        // Priority 2: Check session for manually selected user (for demo/testing)
        return session('chat_user_id', 1);
    }

    /**
     * Set current user in session (simple login for demo)
     */
    public function setCurrentUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        session(['chat_user_id' => $validated['user_id']]);
        session()->save(); // Force save session
        
        $user = \App\Models\User::find($validated['user_id']);
        
        \Log::info('User set in session', [
            'user_id' => $validated['user_id'],
            'user_name' => $user->name,
            'session_id' => session()->getId(),
            'session_data' => session()->all()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'User set successfully',
            'data' => [
                'id' => (int) $user->id, // Ensure ID is integer
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    }

    /**
     * Get current user from session
     */
    public function getCurrentUser(Request $request)
    {
        $userId = $this->getCurrentUserId();
        $user = \App\Models\User::find($userId);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
        
        \Log::info('Get current user', [
            'user_id' => $userId,
            'auth_check' => Auth::check(),
            'auth_id' => Auth::id(),
            'session_id' => session('chat_user_id')
        ]);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => (int) $user->id, // Ensure ID is integer
                'name' => $user->name,
                'email' => $user->email,
                'is_authenticated' => Auth::check(), // Tell frontend if this is from Auth
                'source' => Auth::check() ? 'laravel_auth' : 'manual_selection'
            ]
        ]);
    }

        /**
     * Get all chat rooms (public or for authenticated user)
     */
    public function getRooms(Request $request)
    {
        // Get current user from Auth or session
        $userId = $this->getCurrentUserId();
        
        \Log::info('GetRooms called', [
            'user_id' => $userId,
            'auth_check' => Auth::check(),
            'auth_id' => Auth::id(),
            'session_user_id' => session('chat_user_id')
        ]);
        
        // Only get rooms where user is a member (both group and private)
        $rooms = ChatRoom::where('is_active', true)
            ->whereHas('members', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['latestMessage.user', 'members'])
            ->withCount('members')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Add unread count for each room
        $rooms->each(function($room) use ($userId) {
            $room->unread_count = $this->getUnreadCount($room->id, $userId);
        });

        \Log::info('GetRooms result', [
            'user_id' => $userId,
            'rooms_count' => $rooms->count(),
            'room_ids' => $rooms->pluck('id')
        ]);

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

        // Mark messages as read for current user
        $userId = $this->getCurrentUserId();
        $this->markAsRead($roomId, $userId);

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
        
        // Get current user from Auth or session
        $userId = $this->getCurrentUserId();

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
        
        // Broadcast room update to notify other users about new messages
        broadcast(new \App\Events\RoomUpdated($roomId, $userId));

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

        // Get current user from Auth or session
        $creatorId = $this->getCurrentUserId();

        $room = ChatRoom::create([
            'room_name' => $validated['room_name'],
            'room_type' => $validated['room_type'],
            'subject_id' => $validated['subject_id'] ?? null,
            'created_by' => $creatorId,
            'is_active' => true
        ]);

        // Add creator as admin of the room
        $room->members()->attach($creatorId, [
            'role' => 'admin',
            'joined_at' => now()
        ]);

        // Add other members (optional)
        if (isset($validated['members'])) {
            foreach ($validated['members'] as $memberId) {
                // Skip if already added (creator)
                if ($memberId == $creatorId) continue;
                
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
        // Get current user from Auth or session
        $currentUserId = $this->getCurrentUserId();
        
        $users = \App\Models\User::select('id', 'name', 'email')
            ->where('id', '!=', $currentUserId) // Exclude current user
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

        // Get current user from Auth or session
        $userId = $this->getCurrentUserId();
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

    /**
     * Get unread message count for a room
     */
    protected function getUnreadCount($roomId, $userId)
    {
        // Get last_read_at from pivot table
        $member = \DB::table('chat_room_members')
            ->where('room_id', $roomId)
            ->where('user_id', $userId)
            ->first();

        if (!$member) {
            return 0;
        }

        $lastReadAt = $member->last_read_at;

        // If never read, count all messages not from this user
        if (!$lastReadAt) {
            return ChatMessage::where('room_id', $roomId)
                ->where('user_id', '!=', $userId)
                ->where('is_deleted', false)
                ->count();
        }

        // Count messages after last_read_at and not from this user
        return ChatMessage::where('room_id', $roomId)
            ->where('user_id', '!=', $userId)
            ->where('is_deleted', false)
            ->where('created_at', '>', $lastReadAt)
            ->count();
    }

    /**
     * Mark messages as read for a room
     */
    protected function markAsRead($roomId, $userId)
    {
        \DB::table('chat_room_members')
            ->where('room_id', $roomId)
            ->where('user_id', $userId)
            ->update(['last_read_at' => now()]);

        // Broadcast room updated event to update badge for other tabs
        broadcast(new \App\Events\RoomUpdated($roomId, $userId));
    }

    /**
     * API endpoint to manually mark room as read
     */
    public function markRoomAsRead(Request $request, $roomId)
    {
        $userId = $this->getCurrentUserId();
        $this->markAsRead($roomId, $userId);

        return response()->json([
            'success' => true,
            'message' => 'Room marked as read'
        ]);
    }
}
