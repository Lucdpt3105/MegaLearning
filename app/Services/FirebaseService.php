<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;
use Kreait\Firebase\Storage;

class FirebaseService
{
    protected $database;
    protected $storage;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database.url'));

        $this->database = $factory->createDatabase();
        $this->storage = $factory->createStorage();
    }

    /**
     * Send a message to a chat room
     */
    public function sendMessage($roomId, $data)
    {
        return $this->database
            ->getReference("chats/{$roomId}/messages")
            ->push([
                'user_id' => $data['user_id'],
                'user_name' => $data['user_name'],
                'message' => $data['message'],
                'timestamp' => time(),
                'created_at' => now()->toIso8601String(),
            ]);
    }

    /**
     * Get all messages from a chat room
     */
    public function getMessages($roomId, $limit = 100)
    {
        return $this->database
            ->getReference("chats/{$roomId}/messages")
            ->orderByChild('timestamp')
            ->limitToLast($limit)
            ->getValue();
    }

    /**
     * Create a new chat room
     */
    public function createRoom($data)
    {
        return $this->database
            ->getReference('rooms')
            ->push([
                'name' => $data['name'],
                'type' => $data['type'] ?? 'group', // group, private, class
                'created_by' => $data['created_by'],
                'members' => $data['members'] ?? [],
                'created_at' => now()->toIso8601String(),
            ]);
    }

    /**
     * Get all chat rooms
     */
    public function getRooms()
    {
        return $this->database
            ->getReference('rooms')
            ->getValue();
    }

    /**
     * Get a specific room
     */
    public function getRoom($roomId)
    {
        return $this->database
            ->getReference("rooms/{$roomId}")
            ->getValue();
    }

    /**
     * Update user online status
     */
    public function updateUserStatus($userId, $status)
    {
        return $this->database
            ->getReference("users/{$userId}/status")
            ->set([
                'online' => $status,
                'last_seen' => now()->toIso8601String(),
            ]);
    }

    /**
     * Get database reference (for custom queries)
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Get storage instance (for file uploads)
     */
    public function getStorage()
    {
        return $this->storage;
    }
}
