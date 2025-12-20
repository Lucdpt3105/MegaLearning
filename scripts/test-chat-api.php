<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Chat API ===\n\n";

// Check if user is authenticated
echo "1. Checking authentication...\n";
$user = \App\Models\User::first(); // Get any user
if (!$user) {
    echo "   ❌ No users in database!\n";
    exit(1);
}
echo "   ✅ Found user: {$user->name} (ID: {$user->id}, Email: {$user->email})\n\n";

// Check chat rooms
echo "2. Checking chat rooms...\n";
$rooms = \App\Models\ChatRoom::whereHas('members', function($query) use ($user) {
    $query->where('user_id', $user->id);
})
->with(['latestMessage.user', 'members'])
->where('is_active', true)
->orderBy('updated_at', 'desc')
->get();

echo "   Found {$rooms->count()} rooms for this user\n\n";

if ($rooms->count() > 0) {
    echo "3. Room details:\n";
    foreach ($rooms as $room) {
        echo "   ---\n";
        echo "   Room ID: {$room->id}\n";
        echo "   Name: {$room->room_name}\n";
        echo "   Type: {$room->room_type}\n";
        echo "   Members: " . $room->members->count() . "\n";
        
        if ($room->latestMessage) {
            echo "   Latest message: " . substr($room->latestMessage->message_text, 0, 50) . "\n";
            echo "   Updated at: {$room->updated_at}\n";
        } else {
            echo "   No messages yet\n";
        }
    }
} else {
    echo "   ℹ️  No rooms found. User might not be a member of any chat room.\n";
}

echo "\n=== Test Complete ===\n";
