<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Example: Authenticate user for private chat room channels
Broadcast::channel('chat-room.{roomId}', function ($user, $roomId) {
    // For now, return true to allow all authenticated users
    // In production, check if user is a member of the room:
    // return \App\Models\ChatRoom::find($roomId)?->members->contains($user->id);
    return true;
});

// Presence channel example (shows who's online in a room)
Broadcast::channel('presence-chat-room.{roomId}', function ($user, $roomId) {
    // Return user data to be shown in presence list
    // Check if user is member of room
    $room = \App\Models\ChatRoom::find($roomId);
    if ($room && $room->members->contains($user->id)) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
    return null;
});
