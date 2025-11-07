<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    protected $table = 'topics';
    protected $primaryKey = 'topic_id';
    public $timestamps = false;

    protected $fillable = [
        'topic_name',
        'topic_subject_id'
    ];

    /**
     * Get the subject that owns this topic
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'topic_subject_id', 'subject_id');
    }

    /**
     * Get all questions for this topic
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'question_topic_id', 'topic_id');
    }
}
