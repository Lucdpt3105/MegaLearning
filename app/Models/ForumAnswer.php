<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumAnswer extends Model
{
    protected $table = 'forumanswers';
    protected $primaryKey = 'forum_answer_id';
    public $incrementing = true;
    protected $keyType = 'int';
    // Only created_at exists in table; disable automatic timestamps to avoid updated_at inserts
    // public $timestamps = false;
    const UPDATED_AT = null;
    protected $fillable = ['forum_question_id', 'user_id', 'answer_content', 'parent_id'];
    protected $casts = [
        'created_at' => 'datetime', // ensure Carbon instance for diffForHumans()
    ];

    public function question() {
        return $this->belongsTo(ForumQuestion::class, 'forum_question_id', 'forum_question_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function votes() {
        return $this->hasMany(Vote::class, 'forum_answer_id');
    }

    public function parent() {
        return $this->belongsTo(ForumAnswer::class, 'parent_id', 'forum_answer_id');
    }

    public function children() {
        return $this->hasMany(ForumAnswer::class, 'parent_id', 'forum_answer_id');
    }
}
