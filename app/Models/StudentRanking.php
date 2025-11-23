<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentRanking extends Model
{
    protected $fillable = [
        'student_id',
        'class_room_id',
        'subject_id',
        'gpa',
        'rank',
        'total_students',
        'average_score',
        'total_exams_taken',
        'total_exams_passed',
        'attendance_rate',
        'calculated_at',
    ];

    protected $casts = [
        'gpa' => 'decimal:2',
        'average_score' => 'decimal:2',
        'attendance_rate' => 'decimal:2',
        'calculated_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
