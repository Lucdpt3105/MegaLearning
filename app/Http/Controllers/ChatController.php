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
        // Lấy userId nếu đã đăng nhập, nếu không thì lấy tất cả rooms public
        $userId = Auth::check() ? Auth::id() : null;
        
        if ($userId) {
            // Get all rooms user is member of
            $rooms = ChatRoom::whereHas('members', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['latestMessage.user', 'members'])
            ->where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get();
        } else {
            // Get all public rooms (group type)
            $rooms = ChatRoom::where('is_active', true)
                ->where('room_type', 'group')
                ->with(['latestMessage.user', 'members'])
                ->orderBy('updated_at', 'desc')
                ->get();
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
        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            'room_type' => 'required|in:group,private,subject',
            'subject_id' => 'nullable|exists:subjects,subject_id',
            'members' => 'array',
            'members.*' => 'exists:users,id',
            'include_ai' => 'boolean'
        ]);

        // Use authenticated user ID or default to 1 (guest)
        $creatorId = Auth::check() ? Auth::id() : 1;

        $room = ChatRoom::create([
            'room_name' => $validated['room_name'],
            'room_type' => $validated['room_type'],
            'subject_id' => $validated['subject_id'] ?? null,
            'created_by' => $creatorId,
            'is_active' => true
        ]);

        // Add creator as admin if logged in
        if (Auth::check()) {
            $room->members()->attach($creatorId, [
                'role' => 'admin',
                'joined_at' => now()
            ]);
        }

        // Add other members
        if (isset($validated['members'])) {
            foreach ($validated['members'] as $memberId) {
                $room->members()->attach($memberId, [
                    'role' => 'member',
                    'joined_at' => now()
                ]);
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

        return redirect()->route('chat.show', $room->room_id)
            ->with('success', 'Chat room created successfully');
    }

    /**
     * Send message to room
     */
    public function sendMessage(Request $request, $roomId)
    {
        $validated = $request->validate([
            'message_text' => 'required|string|max:5000',
            'message_type' => 'in:text,image,file',
            'file_url' => 'nullable|string|max:500'
        ]);

        $room = ChatRoom::findOrFail($roomId);

        // Use authenticated user ID or default to 1 (guest)
        $userId = Auth::check() ? Auth::id() : 1;

        // Check if user is member (only for private/subject rooms)
        if ($room->room_type !== 'group' && Auth::check()) {
            if (!$room->members->contains('id', $userId)) {
                abort(403, 'You are not a member of this room');
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
        broadcast(new MessageSent($message))->toOthers();

        // Trigger AI response if configured and AI is a member
        if ($this->aiService->isConfigured()) {
            $aiUser = $this->aiService->getAIUser();
            if ($aiUser && $room->members->contains($aiUser->id)) {
                dispatch(function () use ($room, $message) {
                    $this->handleAIResponse($room, $message);
                })->afterResponse();
            }
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
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
                    'room_id' => $room->room_id,
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
                'room_id' => $room->room_id
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
}
