<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClassRoom extends Model
{
    protected $fillable = [
        'name',
        'code',
        'category_id',
        'subject_id',
        'teacher_id',
        'description',
        'max_students',
        'status',
        'start_date',
        'end_date',
        'settings',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'settings' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(ClassEnrollment::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_enrollments', 'class_room_id', 'student_id')
            ->withPivot('status', 'enrolled_at', 'dropped_at', 'notes')
            ->withTimestamps();
    }

    public function videoCalls(): HasMany
    {
        return $this->hasMany(VideoCall::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function rankings(): HasMany
    {
        return $this->hasMany(StudentRanking::class);
    }

    public function chatRoom()
    {
        return $this->hasOne(ChatRoom::class, 'class_room_id');
    }
}
