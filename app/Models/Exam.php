<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exam extends Model
{
    protected $table = 'exams';
    protected $primaryKey = 'exam_id';
    public $timestamps = false;

    protected $fillable = [
        'exam_title',
        'exam_subject_id',
        'exam_date',
        'exam_duration',
        'total_marks'
    ];

    protected $casts = [
        'exam_date' => 'date'
    ];

    /**
     * Get the subject that owns this exam
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'exam_subject_id', 'subject_id');
    }

    /**
     * Get all questions for this exam
     */
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(
            Question::class,
            'examquestions',
            'exam_question_exam_id',
            'exam_question_question_id',
            'exam_id',
            'question_id'
        );
    }
}
