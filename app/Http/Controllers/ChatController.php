<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Services\AIService;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Display chat interface
     */
    public function index()
    {
        // Only show rooms for authenticated users
        $userId = Auth::check() ? Auth::id() : null;
        
        if ($userId) {
            // Get ONLY rooms where this specific user is a member
            $rooms = ChatRoom::whereHas('members', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['latestMessage.user', 'members'])
            ->where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get();
        } else {
            // Guest users: return empty collection (must login to see rooms)
            $rooms = collect([]);
        }

        return view('chat.index', compact('rooms'));
    }

    /**
     * Show specific chat room
     */
    public function show($roomId)
    {
        $userId = Auth::check() ? Auth::id() : null;
        
        $room = ChatRoom::with(['members', 'subject'])
            ->findOrFail($roomId);

        // Allow access for public rooms, check membership for others
        if ($userId && $room->room_type !== 'group') {
            // Check if user is member for non-public rooms
            if (!$room->members->contains('id', $userId)) {
                abort(403, 'You are not a member of this room');
            }
        }

        // Get messages
        $messages = ChatMessage::where('room_id', $roomId)
            ->with('user')
            ->active()
            ->orderBy('created_at', 'asc')
            ->get();

        return view('chat.room', compact('room', 'messages'));
    }

    /**
     * Create new chat room
     */
    public function store(Request $request)
    {
        try {
            \Log::info('Creating chat room', [
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            $validated = $request->validate([
                'room_name' => 'required|string|max:255',
                'room_type' => 'required|in:group,private,subject',
                'subject_id' => 'nullable|exists:subjects,subject_id',
                'members' => 'nullable|array',
                'members.*' => 'exists:users,id',
                'include_ai' => 'nullable|boolean'
            ]);

            // Use authenticated user ID - must be logged in
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to create a room'
                ], 401);
            }

            $creatorId = Auth::id();

            $room = ChatRoom::create([
                'room_name' => $validated['room_name'],
                'room_type' => $validated['room_type'],
                'subject_id' => $validated['subject_id'] ?? null,
                'created_by' => $creatorId,
                'is_active' => true
            ]);

            // Add creator as admin
            $room->members()->attach($creatorId, [
                'role' => 'admin',
                'joined_at' => now()
            ]);

            // Add other members
            if (isset($validated['members']) && is_array($validated['members'])) {
                foreach ($validated['members'] as $memberId) {
                    if ($memberId != $creatorId) {
                        $room->members()->attach($memberId, [
                            'role' => 'member',
                            'joined_at' => now()
                        ]);
                    }
                }
            }

            // Add AI bot if requested and configured
            if (($validated['include_ai'] ?? false) && $this->aiService->isConfigured()) {
                $aiUser = $this->aiService->getAIUser();
                if ($aiUser && !$room->members->contains($aiUser->id)) {
                    $room->members()->attach($aiUser->id, [
                        'role' => 'bot',
                        'joined_at' => now()
                    ]);
                }
            }

            // Load relationships
            $room->load('members', 'latestMessage');

            \Log::info('Chat room created successfully', ['room_id' => $room->id]);

            // Return JSON for API
            return response()->json([
                'success' => true,
                'room' => $room,
                'message' => 'Chat room created successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error creating room', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating chat room', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error creating room: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send message to room
     */
    public function sendMessage(Request $request, $roomId)
    {
        try {
            \Log::info('Sending message', [
                'room_id' => $roomId,
                'user_id' => Auth::check() ? Auth::id() : 'guest',
                'message' => $request->message_text
            ]);

            $validated = $request->validate([
                'message_text' => 'nullable|string|max:5000',
                'message_type' => 'nullable|in:text,image,file',
                'file_url' => 'nullable|string|max:500'
            ]);
            
            // Ensure at least message_text or file_url is provided
            if (empty($validated['message_text']) && empty($validated['file_url'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng nhập tin nhắn hoặc đính kèm file'
                ], 422);
            }

            $room = ChatRoom::findOrFail($roomId);

            // Use authenticated user ID or default to 1 (guest)
            $userId = Auth::check() ? Auth::id() : 1;

            // Check if user is member (only for private/subject rooms)
            if ($room->room_type !== 'group' && Auth::check()) {
                if (!$room->members->contains('id', $userId)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bạn không phải thành viên của phòng chat này'
                    ], 403);
                }
            }

            $message = ChatMessage::create([
                'room_id' => $roomId,
                'user_id' => $userId,
                'message_text' => $validated['message_text'],
                'message_type' => $validated['message_type'] ?? 'text',
                'file_url' => $validated['file_url'] ?? null,
            ]);

            // Load relationships
            $message->load('user');

            // Update room's updated_at
            $room->touch();

            // Broadcast event
            try {
                broadcast(new MessageSent($message))->toOthers();
            } catch (\Exception $e) {
                \Log::warning('Broadcast failed (Pusher not configured)', ['error' => $e->getMessage()]);
            }

            // Trigger AI response if configured and AI is a member
            if ($this->aiService->isConfigured()) {
                $aiUser = $this->aiService->getAIUser();
                if ($aiUser && $room->members->contains($aiUser->id)) {
                    dispatch(function () use ($room, $message) {
                        $this->handleAIResponse($room, $message);
                    })->afterResponse();
                }
            }

            \Log::info('Message sent successfully', ['message_id' => $message->id]);

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error sending message', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle AI response generation
     */
    protected function handleAIResponse(ChatRoom $room, ChatMessage $userMessage)
    {
        try {
            // Don't respond to AI's own messages
            if ($userMessage->user_id === $this->aiService->getAIUser()->id) {
                return;
            }

            // Generate AI response
            $aiResponse = $this->aiService->generateResponse($room, $userMessage);

            if ($aiResponse) {
                // Create AI message
                $aiMessage = ChatMessage::create([
                    'room_id' => $room->id,
                    'user_id' => $this->aiService->getAIUser()->id,
                    'message_text' => $aiResponse,
                    'message_type' => 'text',
                ]);

                // Load relationships
                $aiMessage->load('user');

                // Broadcast AI message
                broadcast(new MessageSent($aiMessage));
            }
        } catch (\Exception $e) {
            \Log::error('AI Response Error', [
                'error' => $e->getMessage(),
                'room_id' => $room->id
            ]);
        }
    }

    /**
     * Join a chat room
     */
    public function join($roomId)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Please login to join rooms');
        }

        $room = ChatRoom::findOrFail($roomId);
        $userId = Auth::id();

        // Check if already member
        if ($room->members->contains('id', $userId)) {
            return back()->with('info', 'You are already a member');
        }

        $room->members()->attach($userId, [
            'role' => 'member',
            'joined_at' => now()
        ]);

        return redirect()->route('chat.show', $roomId)
            ->with('success', 'Joined room successfully');
    }

    /**
     * Leave a chat room
     */
    public function leave($roomId)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Please login first');
        }

        $room = ChatRoom::findOrFail($roomId);
        $userId = Auth::id();

        $room->members()->detach($userId);

        return redirect()->route('chat.index')
            ->with('success', 'Left room successfully');
    }
    
    /**
     * Get all chat rooms (API)
     * Only returns rooms where the authenticated user is a member
     */
    public function getRooms(Request $request)
    {
        $userId = Auth::check() ? Auth::id() : null;
        
        if ($userId) {
            // Get ONLY rooms where this specific user is a member
            $rooms = ChatRoom::whereHas('members', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['latestMessage.user', 'members'])
            ->where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get();
            
            // Add unread count for each room
            $rooms->each(function($room) use ($userId) {
                $room->unread_count = $this->getUnreadCount($room->id, $userId);
            });
            
            \Log::info('getRooms() - User authenticated', [
                'user_id' => $userId,
                'rooms_count' => $rooms->count()
            ]);
        } else {
            // Guest users: return empty array (must login to see rooms)
            $rooms = collect([]);
            
            \Log::info('getRooms() - Guest user, returning empty rooms');
        }
        
        return response()->json([
            'success' => true,
            'rooms' => $rooms
        ]);
    }
    
    /**
     * Get unread message count for a room
     */
    private function getUnreadCount($roomId, $userId)
    {
        // Count messages in room that user hasn't read
        return ChatMessage::where('room_id', $roomId)
            ->where('user_id', '!=', $userId) // Don't count own messages
            ->whereNotExists(function($query) use ($userId) {
                $query->select(\DB::raw(1))
                    ->from('chat_message_reads')
                    ->whereColumn('chat_message_reads.message_id', 'chat_messages.id')
                    ->where('chat_message_reads.user_id', $userId);
            })
            ->count();
    }
    
    /**
     * Get total unread count across all rooms
     */
    public function getTotalUnreadCount(Request $request)
    {
        $userId = Auth::check() ? Auth::id() : null;
        
        if (!$userId) {
            return response()->json([
                'success' => true,
                'unread_count' => 0
            ]);
        }
        
        // Get all room IDs user is member of
        $roomIds = ChatRoom::whereHas('members', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->pluck('id');
        
        // Count unread messages across all rooms
        $totalUnread = ChatMessage::whereIn('room_id', $roomIds)
            ->where('user_id', '!=', $userId)
            ->whereNotExists(function($query) use ($userId) {
                $query->select(\DB::raw(1))
                    ->from('chat_message_reads')
                    ->whereColumn('chat_message_reads.message_id', 'chat_messages.id')
                    ->where('chat_message_reads.user_id', $userId);
            })
            ->count();
        
        return response()->json([
            'success' => true,
            'unread_count' => $totalUnread
        ]);
    }
    
    /**
     * Get messages for a room (API)
     */
    public function getMessages($roomId)
    {
        $messages = ChatMessage::where('room_id', $roomId)
            ->with('user')
            ->active()
            ->orderBy('created_at', 'asc')
            ->get();
            
        // Mark messages as read for authenticated user
        $userId = Auth::check() ? Auth::id() : session('chat_user_id');
        if ($userId) {
            $this->markMessagesAsRead($roomId, $userId);
        }
            
        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }
    
    /**
     * Mark all messages in a room as read for user
     */
    private function markMessagesAsRead($roomId, $userId)
    {
        $messageIds = ChatMessage::where('room_id', $roomId)
            ->where('user_id', '!=', $userId)
            ->pluck('id');
            
        foreach ($messageIds as $messageId) {
            \DB::table('chat_message_reads')->insertOrIgnore([
                'message_id' => $messageId,
                'user_id' => $userId,
                'read_at' => now()
            ]);
        }
    }
    
    /**
     * Mark messages as read (API endpoint)
     */
    public function markAsRead(Request $request, $roomId)
    {
        $userId = Auth::check() ? Auth::id() : session('chat_user_id');
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        $this->markMessagesAsRead($roomId, $userId);
        
        return response()->json([
            'success' => true,
            'message' => 'Messages marked as read'
        ]);
    }
    
    /**
     * Get all users for chat (API)
     */
    public function getUsers(Request $request)
    {
        $users = \App\Models\User::select('id', 'name', 'email')
            ->orderBy('name')
            ->get();
            
        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }
    
    /**
     * Get current user from session (API)
     */
    public function getCurrentUser(Request $request)
    {
        // Check if user is authenticated via Laravel Auth
        if (Auth::check()) {
            $user = Auth::user();
            
            // Determine user role
            $role = 'student'; // default
            if ($user->hasRole('admin')) {
                $role = 'admin';
            } elseif ($user->hasRole('teacher')) {
                $role = 'teacher';
            } elseif ($user->hasRole('student')) {
                $role = 'student';
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $role,
                    'is_authenticated' => true,
                    'source' => 'laravel_auth'
                ]
            ]);
        }
        
        // No user found - return guest status (not an error)
        return response()->json([
            'success' => true,
            'data' => [
                'id' => null,
                'name' => 'Guest',
                'email' => null,
                'role' => 'guest',
                'is_authenticated' => false,
                'source' => 'guest'
            ]
        ]);
    }
    
    /**
     * Set current user in session (API)
     */
    public function setUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);
        
        session(['chat_user_id' => $validated['user_id']]);
        
        $user = \App\Models\User::find($validated['user_id']);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_authenticated' => false,
                'source' => 'session'
            ]
        ]);
    }
    
    /**
     * Add member to room (API)
     */
    public function addMember(Request $request, $roomId)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        
        $room = ChatRoom::findOrFail($roomId);
        
        // Check if already member
        if ($room->members->contains('id', $validated['user_id'])) {
            return response()->json([
                'success' => false,
                'message' => 'User is already a member'
            ], 400);
        }
        
        $room->members()->attach($validated['user_id'], [
            'role' => 'member',
            'joined_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Member added successfully'
        ]);
    }
    
    /**
     * Remove member from room (API)
     */
    public function removeMember($roomId, $userId)
    {
        $room = ChatRoom::findOrFail($roomId);
        $room->members()->detach($userId);
        
        return response()->json([
            'success' => true,
            'message' => 'Member removed successfully'
        ]);
    }
    
    /**
     * Update room (API)
     */
    public function update(Request $request, $roomId)
    {
        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
        ]);
        
        $room = ChatRoom::findOrFail($roomId);
        $room->update($validated);
        
        return response()->json([
            'success' => true,
            'room' => $room
        ]);
    }
    
    /**
     * Upload file for chat (images, documents, etc.)
     */
    public function uploadFile(Request $request)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để upload file'
                ], 401);
            }

            $file = $request->file('file');
            
            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy file'
                ], 400);
            }

            // Check file size (10MB for images, 50MB for other files)
            $maxSize = $request->input('type') === 'image' ? 10240 : 51200; // KB
            
            if ($file->getSize() > $maxSize * 1024) {
                $maxMB = $maxSize / 1024;
                return response()->json([
                    'success' => false,
                    'message' => "File quá lớn. Kích thước tối đa: {$maxMB}MB"
                ], 422);
            }

            // Validate file type based on upload type
            $type = $request->input('type', 'file');
            
            if ($type === 'image') {
                $allowedMimes = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
                $extension = strtolower($file->getClientOriginalExtension());
                
                if (!in_array($extension, $allowedMimes)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Chỉ chấp nhận ảnh: JPG, PNG, GIF, WEBP'
                    ], 422);
                }
            } else {
                // Block dangerous file types
                $blockedExtensions = ['exe', 'bat', 'sh', 'php', 'js', 'html', 'htm'];
                $extension = strtolower($file->getClientOriginalExtension());
                
                if (in_array($extension, $blockedExtensions)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Loại file này không được phép upload'
                    ], 422);
                }
            }
            
            $userId = Auth::id();
            
            // Create unique filename
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '_' . $userId . '.' . $extension;
            
            // Ensure storage directory exists
            $storagePath = storage_path('app/public/chat_files');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }
            
            // Store in public/storage/chat_files
            $path = $file->storeAs('chat_files', $filename, 'public');
            
            if (!$path) {
                throw new \Exception('Không thể lưu file');
            }
            
            // Get full URL
            $url = asset('storage/' . $path);
            
            return response()->json([
                'success' => true,
                'file_url' => $url,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'file_size_formatted' => $this->formatFileSize($file->getSize()),
                'file_type' => $file->getMimeType(),
                'message_type' => $type
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error uploading chat file', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi upload file: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Format file size for display
     */
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
    
    /**
     * Delete room (API)
     */
    public function destroy($roomId)
    {
        $room = ChatRoom::findOrFail($roomId);
        $room->update(['is_active' => false]);
        
        return response()->json([
            'success' => true,
            'message' => 'Room deleted successfully'
        ]);
    }
}
