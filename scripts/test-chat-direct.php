<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🚀 Testing Chat System...\n\n";

// Test 1: Create a room
echo "📋 Test 1: Creating a room...\n";
try {
    $room = \App\Models\ChatRoom::create([
        'room_name' => 'Test Room ' . date('H:i:s'),
        'room_type' => 'group',
        'created_by' => 1,
        'is_active' => true
    ]);
    echo "✅ Room created! ID: {$room->room_id}, Name: {$room->room_name}\n\n";
    
    // Test 2: Send a message
    echo "📋 Test 2: Sending a message...\n";
    $message = \App\Models\ChatMessage::create([
        'room_id' => $room->room_id,
        'user_id' => 1,
        'message_text' => 'Hello from test script! 🎉',
        'message_type' => 'text'
    ]);
    echo "✅ Message sent! ID: {$message->message_id}\n\n";
    
    // Test 3: Get messages
    echo "📋 Test 3: Getting messages...\n";
    $messages = \App\Models\ChatMessage::where('room_id', $room->room_id)->get();
    echo "✅ Found {$messages->count()} message(s)\n";
    foreach ($messages as $msg) {
        echo "  - [{$msg->user->name}]: {$msg->message_text}\n";
    }
    echo "\n";
    
    // Test 4: Get all rooms
    echo "📋 Test 4: Getting all rooms...\n";
    $rooms = \App\Models\ChatRoom::all();
    echo "✅ Found {$rooms->count()} room(s)\n";
    foreach ($rooms as $r) {
        echo "  - ID: {$r->room_id}, Name: {$r->room_name}, Type: {$r->room_type}\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n🎉 Test completed!\n";
echo "\n💡 Now visit: http://localhost:8000/chat-test.html\n";
