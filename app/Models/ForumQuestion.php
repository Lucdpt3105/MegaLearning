<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumQuestion extends Model
{
    //
        protected $table = 'forumquestions';
    protected $fillable = ['user_id', 'title', 'content'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function answers() {
        return $this->hasMany(ForumAnswer::class, 'forum_question_id');
    }

    public function votes() {
        return $this->hasMany(Vote::class, 'forum_question_id');
    }
}
