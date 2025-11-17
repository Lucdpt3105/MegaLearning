<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'title',
        'description',
        'subject_id',
        'class_room_id',
        'created_by',
        'type',
        'duration',
        'total_questions',
        'total_points',
        'passing_score',
        'approval_status',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'start_time',
        'end_time',
        'allow_retake',
        'max_attempts',
        'shuffle_questions',
        'shuffle_answers',
        'show_results_immediately',
        'allow_review',
        'status',
        'settings',
    ];

    protected $casts = [
        'total_points' => 'decimal:2',
        'passing_score' => 'decimal:2',
        'approved_at' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'allow_retake' => 'boolean',
        'shuffle_questions' => 'boolean',
        'shuffle_answers' => 'boolean',
        'show_results_immediately' => 'boolean',
        'allow_review' => 'boolean',
        'settings' => 'array',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'exam_questions')
            ->withPivot(['order', 'points', 'custom_type', 'custom_content', 'custom_answers', 'custom_explanation'])
            ->withTimestamps()
            ->orderBy('exam_questions.order');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(ExamSubmission::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
