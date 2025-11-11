<?php

namespace App\Http\Controllers;

use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->middleware('auth');
        $this->firebase = $firebase;
    }

    /**
     * Display chat interface
     */
    public function index()
    {
        $rooms = $this->firebase->getRooms();
        return view('chat.index', compact('rooms'));
    }

    /**
     * Show specific chat room
     */
    public function show($roomId)
    {
        $room = $this->firebase->getRoom($roomId);
        $messages = $this->firebase->getMessages($roomId);
        
        return view('chat.room', compact('room', 'messages', 'roomId'));
    }

    /**
     * Send a message (API endpoint)
     */
    public function sendMessage(Request $request, $roomId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        $messageData = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'message' => $request->message,
        ];

        $result = $this->firebase->sendMessage($roomId, $messageData);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $result,
        ]);
    }

    /**
     * Get messages (API endpoint)
     */
    public function getMessages($roomId)
    {
        $messages = $this->firebase->getMessages($roomId);

        return response()->json([
            'success' => true,
            'data' => $messages,
        ]);
    }

    /**
     * Create new chat room
     */
    public function createRoom(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:group,private,class',
            'members' => 'array',
        ]);

        $user = Auth::user();

        $roomData = [
            'name' => $request->name,
            'type' => $request->type,
            'created_by' => $user->id,
            'members' => $request->members ?? [$user->id],
        ];

        $result = $this->firebase->createRoom($roomData);

        return response()->json([
            'success' => true,
            'message' => 'Room created successfully',
            'data' => $result,
        ]);
    }
}
