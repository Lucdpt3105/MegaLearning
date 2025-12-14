<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'content',
        'type',
        'correct_answer_count',
        'exam_id',
        'subject_id',
        'topic_id',
        'created_by',
        'points',
        'difficulty',
        'bloom_level',
        'tags',
        'explanation',
        'grading_guide',
        'image_url',
        'audio_url',
        'video_url',
        'order',
        'in_question_bank',
        'usage_count',
        'last_used_at',
    ];

    protected $casts = [
        'points' => 'decimal:2',
        'in_question_bank' => 'boolean',
        'tags' => 'array',
        'usage_count' => 'integer',
        'last_used_at' => 'datetime',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function correctAnswer(): HasMany
    {
        return $this->hasMany(Answer::class)->where('is_correct', true);
    }

    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(Exam::class, 'exam_questions')
            ->withPivot('order', 'points')
            ->withTimestamps();
    }
}
