<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ChatRoom extends Model
{
    protected $fillable = [
        'room_name',
        'room_type', // 'group', 'private', 'subject', 'class'
        'subject_id', // NULL nếu không liên quan đến môn học
        'class_room_id', // NULL nếu không liên quan đến lớp học
        'created_by',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get all messages in this room
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'room_id', 'id')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Get all members in this room
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'chat_room_members',
            'room_id',
            'user_id',
            'id', // Parent key (chat_rooms.id)
            'id'  // Related key (users.id)
        )->withPivot('joined_at', 'role')
          ->withTimestamps();
    }

    /**
     * Get the creator of this room
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the subject related to this room (if any)
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }

    /**
     * Get the class room related to this room (if any)
     */
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_room_id', 'id');
    }

    /**
     * Get latest message
     */
    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class, 'room_id', 'id')
                    ->latest('created_at');
    }
}
