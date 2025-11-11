<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $table = 'questions';
    protected $primaryKey = 'question_id';
    public $timestamps = false;

    protected $fillable = [
        'question_text',
        'question_topic_id',
        'question_type_id',
        'question_difficulty'
    ];

    /**
     * Get the topic that owns this question
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'question_topic_id', 'topic_id');
    }

    /**
     * Get all answers for this question
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'answer_question_id', 'question_id');
    }

    /**
     * Get the correct answer for this question
     */
    public function correctAnswer()
    {
        return $this->hasOne(Answer::class, 'answer_question_id', 'question_id')
                    ->where('is_correct', 1);
    }
}
