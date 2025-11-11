<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $table = 'chat_messages';
    protected $primaryKey = 'message_id';
    public $timestamps = true;

    protected $fillable = [
        'room_id',
        'user_id',
        'message_text',
        'message_type', // 'text', 'image', 'file', 'system'
        'file_url',
        'is_edited',
        'is_deleted'
    ];

    protected $casts = [
        'is_edited' => 'boolean',
        'is_deleted' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = ['time_ago'];

    /**
     * Get the room this message belongs to
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class, 'room_id', 'room_id');
    }

    /**
     * Get the user who sent this message
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get human-readable time
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Scope to get undeleted messages
     */
    public function scopeActive($query)
    {
        return $query->where('is_deleted', false);
    }
}
