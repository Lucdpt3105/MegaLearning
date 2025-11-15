<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Chat Room Members - Last Read At ===\n\n";

$members = DB::table('chat_room_members')
    ->join('chat_rooms', 'chat_room_members.room_id', '=', 'chat_rooms.id')
    ->join('users', 'chat_room_members.user_id', '=', 'users.id')
    ->select(
        'chat_rooms.room_name',
        'users.name as user_name',
        'chat_room_members.last_read_at',
        'chat_room_members.room_id',
        'chat_room_members.user_id'
    )
    ->orderBy('chat_room_members.room_id')
    ->orderBy('chat_room_members.user_id')
    ->get();

foreach ($members as $member) {
    echo "Room: {$member->room_name}\n";
    echo "User: {$member->user_name}\n";
    echo "Last Read: " . ($member->last_read_at ?? 'NULL') . "\n";
    echo "---\n";
}

echo "\n=== Message Counts ===\n\n";

$rooms = DB::table('chat_rooms')
    ->select('id', 'room_name')
    ->get();

foreach ($rooms as $room) {
    $messageCount = DB::table('chat_messages')
        ->where('room_id', $room->id)
        ->count();
    
    echo "Room: {$room->room_name} - Messages: {$messageCount}\n";
}
