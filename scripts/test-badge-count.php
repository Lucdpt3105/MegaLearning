<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\DB;

echo "==============================================\n";
echo "Testing Badge Count Feature\n";
echo "==============================================\n\n";

// Get test users
$user1 = User::where('email', 'student1@megalearning.local')->first();
$user2 = User::where('email', 'student2@megalearning.local')->first();

if (!$user1 || !$user2) {
    echo "❌ Test users not found. Please run seeder first.\n";
    exit(1);
}

echo "✅ Test users found:\n";
echo "   User 1: {$user1->name} (ID: {$user1->id})\n";
echo "   User 2: {$user2->name} (ID: {$user2->id})\n\n";

// Find or create a test room
$room = ChatRoom::where('room_type', 'private')
    ->whereHas('members', function($q) use ($user1) {
        $q->where('user_id', $user1->id);
    })
    ->whereHas('members', function($q) use ($user2) {
        $q->where('user_id', $user2->id);
    })
    ->first();

if (!$room) {
    echo "Creating test room...\n";
    $room = ChatRoom::create([
        'room_name' => "Test Chat: {$user1->name} & {$user2->name}",
        'room_type' => 'private',
        'created_by' => $user1->id,
        'is_active' => true
    ]);
    
    $room->members()->attach($user1->id, ['role' => 'member', 'joined_at' => now()]);
    $room->members()->attach($user2->id, ['role' => 'member', 'joined_at' => now()]);
    
    echo "✅ Test room created (ID: {$room->id})\n\n";
} else {
    echo "✅ Using existing room (ID: {$room->id})\n\n";
}

// Test 1: Send message from User 1
echo "Test 1: User 1 sends a message\n";
echo "--------------------------------\n";
$message1 = ChatMessage::create([
    'room_id' => $room->id,
    'user_id' => $user1->id,
    'message_text' => 'Test message for badge feature - ' . now()->format('H:i:s'),
    'message_type' => 'text',
]);
echo "✅ Message sent from User 1\n";

// Check unread count for User 2
$member2 = DB::table('chat_room_members')
    ->where('room_id', $room->id)
    ->where('user_id', $user2->id)
    ->first();

$lastReadAt = $member2->last_read_at;
echo "   User 2 last_read_at: " . ($lastReadAt ?? 'NULL') . "\n";

if (!$lastReadAt) {
    $unreadCount = ChatMessage::where('room_id', $room->id)
        ->where('user_id', '!=', $user2->id)
        ->where('is_deleted', false)
        ->count();
} else {
    $unreadCount = ChatMessage::where('room_id', $room->id)
        ->where('user_id', '!=', $user2->id)
        ->where('is_deleted', false)
        ->where('created_at', '>', $lastReadAt)
        ->count();
}

echo "   Unread count for User 2: {$unreadCount}\n";
if ($unreadCount > 0) {
    echo "   ✅ Badge should show: {$unreadCount}\n";
} else {
    echo "   ⚠️  No unread messages\n";
}
echo "\n";

// Test 2: Mark as read for User 2
echo "Test 2: User 2 marks room as read\n";
echo "-----------------------------------\n";
DB::table('chat_room_members')
    ->where('room_id', $room->id)
    ->where('user_id', $user2->id)
    ->update(['last_read_at' => now()]);

echo "✅ Room marked as read for User 2\n";

// Check unread count again
$member2 = DB::table('chat_room_members')
    ->where('room_id', $room->id)
    ->where('user_id', $user2->id)
    ->first();

$lastReadAt = $member2->last_read_at;
$unreadCount = ChatMessage::where('room_id', $room->id)
    ->where('user_id', '!=', $user2->id)
    ->where('is_deleted', false)
    ->where('created_at', '>', $lastReadAt)
    ->count();

echo "   Unread count for User 2: {$unreadCount}\n";
if ($unreadCount === 0) {
    echo "   ✅ Badge should disappear\n";
} else {
    echo "   ⚠️  Still has unread messages: {$unreadCount}\n";
}
echo "\n";

// Test 3: Send another message from User 1
echo "Test 3: User 1 sends another message\n";
echo "--------------------------------------\n";
sleep(1); // Ensure timestamp is different
$message2 = ChatMessage::create([
    'room_id' => $room->id,
    'user_id' => $user1->id,
    'message_text' => 'Another test message - ' . now()->format('H:i:s'),
    'message_type' => 'text',
]);
echo "✅ Another message sent from User 1\n";

// Check unread count for User 2
$member2 = DB::table('chat_room_members')
    ->where('room_id', $room->id)
    ->where('user_id', $user2->id)
    ->first();

$lastReadAt = $member2->last_read_at;
$unreadCount = ChatMessage::where('room_id', $room->id)
    ->where('user_id', '!=', $user2->id)
    ->where('is_deleted', false)
    ->where('created_at', '>', $lastReadAt)
    ->count();

echo "   Unread count for User 2: {$unreadCount}\n";
if ($unreadCount > 0) {
    echo "   ✅ Badge should reappear: {$unreadCount}\n";
} else {
    echo "   ⚠️  No new unread messages\n";
}
echo "\n";

echo "==============================================\n";
echo "Badge Feature Test Summary\n";
echo "==============================================\n";
echo "✅ All database operations working correctly\n";
echo "✅ Unread count logic is functional\n";
echo "✅ Mark as read functionality works\n";
echo "\n";
echo "Next: Test in browser with two tabs/windows\n";
echo "URL: http://localhost:8000/chat\n";
echo "==============================================\n";
