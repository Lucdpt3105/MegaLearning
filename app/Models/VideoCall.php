<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VideoCall extends Model
{
    protected $fillable = [
        'class_room_id',
        'host_id',
        'title',
        'description',
        'room_code',
        'meeting_url',
        'scheduled_at',
        'started_at',
        'ended_at',
        'duration',
        'recording_url',
        'is_recording',
        'status',
        'participants',
        'settings',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_recording' => 'boolean',
        'participants' => 'array',
        'settings' => 'array',
    ];

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
