<?php
/**
 * Check chat rooms and members
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ChatRoom;
use App\Models\User;

echo "=" . str_repeat("=", 80) . "\n";
echo "  CHAT ROOMS STATUS\n";
echo "=" . str_repeat("=", 80) . "\n\n";

$rooms = ChatRoom::with('members')->get();

echo "Total rooms: " . $rooms->count() . "\n\n";

if ($rooms->count() > 0) {
    foreach ($rooms as $room) {
        echo "Room ID: {$room->id}\n";
        echo "Name: {$room->room_name}\n";
        echo "Type: {$room->room_type}\n";
        echo "Members ({$room->members->count()}):\n";
        
        foreach ($room->members as $member) {
            $role = $member->pivot->role ?? 'member';
            echo "  - {$member->name} (ID: {$member->id}, Role: {$role})\n";
        }
        
        echo "\n";
    }
} else {
    echo "⚠️  No rooms found. Users can create new rooms in the chat interface.\n\n";
}

echo str_repeat("=", 80) . "\n";

// Check if all users can be accessed
echo "\nVerifying all users are accessible:\n\n";

$testUsers = [9, 10, 11, 12, 13, 14];
$accessibleCount = 0;

foreach ($testUsers as $userId) {
    $user = User::find($userId);
    if ($user) {
        echo "✅ User ID {$userId}: {$user->name}\n";
        $accessibleCount++;
    } else {
        echo "❌ User ID {$userId}: NOT FOUND\n";
    }
}

echo "\n";
echo "Accessible users: {$accessibleCount}/" . count($testUsers) . "\n";
echo str_repeat("=", 80) . "\n";

if ($accessibleCount === count($testUsers)) {
    echo "✅ All test users are ready!\n";
    echo "\nYou can now:\n";
    echo "1. Run: scripts\\test-all-users.bat\n";
    echo "2. Open: http://localhost:8000/chat\n";
    echo "3. Select different users in different tabs\n";
    echo "4. Create rooms and test private/group chat\n";
} else {
    echo "⚠️  Some users are missing. Run: php scripts/create-test-chat-users.php\n";
}

echo str_repeat("=", 80) . "\n";
