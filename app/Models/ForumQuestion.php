<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumQuestion extends Model
{
    //
        protected $table = 'forumquestions';
    protected $primaryKey = 'forum_question_id';
    public $incrementing = true;
    protected $keyType = 'int';
    // Enable automatic updated_at management (column added via migration)
    protected $fillable = ['user_id', 'title', 'content', 'image_path', 'tags'];
    
    protected $casts = [
        'tags' => 'array',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function answers() {
        return $this->hasMany(ForumAnswer::class, 'forum_question_id', 'forum_question_id');
    }

    public function votes() {
        return $this->hasMany(Vote::class, 'forum_question_id', 'forum_question_id');
    }

    protected static function booted(): void
    {
        static::creating(function (ForumQuestion $model) {
            $model->updated_at = null;
        });
    }
}
