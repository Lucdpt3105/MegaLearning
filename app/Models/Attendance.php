<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $table = 'attendance';
    
    protected $fillable = [
        'class_room_id',
        'student_id',
        'video_call_id',
        'date',
        'status',
        'checked_in_at',
        'checked_out_at',
        'duration',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function videoCall(): BelongsTo
    {
        return $this->belongsTo(VideoCall::class);
    }
}
