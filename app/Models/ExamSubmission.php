<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamSubmission extends Model
{
    protected $fillable = [
        'exam_id',
        'student_id',
        'attempt_number',
        'started_at',
        'submitted_at',
        'time_spent',
        'answers',
        'essay_file_path',
        'score',
        'grading_status',
        'feedback',
        'graded_by',
        'graded_at',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'answers' => 'array',
        'score' => 'decimal:2',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
