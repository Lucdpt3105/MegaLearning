<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 'subject_id';
    public $timestamps = false;

    protected $fillable = [
        'subject_name'
    ];

    /**
     * Get all topics for this subject
     */
    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class, 'topic_subject_id', 'subject_id');
    }

    /**
     * Get all exams for this subject
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'exam_subject_id', 'subject_id');
    }
}
