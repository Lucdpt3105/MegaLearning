<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['user_id', 'forum_question_id', 'forum_answer_id', 'value'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function question() {
        return $this->belongsTo(ForumQuestion::class, 'forumquestion_id');
    }

    public function answer() {
        return $this->belongsTo(ForumAnswer::class, 'forumanswer_id');
    }
}
