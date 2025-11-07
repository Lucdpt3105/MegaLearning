<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    protected $table = 'answers';
    protected $primaryKey = 'answer_id';
    public $timestamps = false;

    protected $fillable = [
        'answer_text',
        'answer_question_id',
        'is_correct'
    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

    /**
     * Get the question that owns this answer
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'answer_question_id', 'question_id');
    }
}
