<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'title',
        'description',
        'subject_id',
        'created_by',
        'type',
        'duration',
        'total_questions',
        'total_points',
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
        'settings',
    ];

    protected $casts = [
        'total_points' => 'decimal:2',
        'approved_at' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'allow_retake' => 'boolean',
        'shuffle_questions' => 'boolean',
        'shuffle_answers' => 'boolean',
        'show_results_immediately' => 'boolean',
        'settings' => 'array',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order');
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
