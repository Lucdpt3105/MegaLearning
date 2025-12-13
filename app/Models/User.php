<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'bio',
        'last_login_at',
        'is_locked',
        'student_id',
        'gender',
        'date_of_birth',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'is_locked' => 'boolean',
        ];
    }

    /**
     * Get all chat rooms this user is a member of
     */
    public function chatRooms(): BelongsToMany
    {
        return $this->belongsToMany(
            ChatRoom::class,
            'chat_room_members',
            'user_id',
            'room_id'
        )->withPivot('joined_at', 'role')
          ->withTimestamps();
    }

    // Relationships cho Giáo viên
    public function teachingSubjects()
    {
        return $this->hasMany(Subject::class, 'teacher_id');
    }

    public function teachingClasses()
    {
        return $this->hasMany(ClassRoom::class, 'teacher_id');
    }

    public function createdExams()
    {
        return $this->hasMany(Exam::class, 'created_by');
    }

    public function createdQuestions()
    {
        return $this->hasMany(Question::class, 'created_by');
    }

    public function uploadedDocuments()
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    public function hostedVideoCalls()
    {
        return $this->hasMany(VideoCall::class, 'host_id');
    }

    // Relationships cho Học sinh
    public function enrolledClasses()
    {
        return $this->belongsToMany(ClassRoom::class, 'class_enrollments', 'student_id', 'class_room_id')
            ->withPivot('status', 'enrolled_at', 'dropped_at', 'notes')
            ->withTimestamps();
    }

    public function examSubmissions()
    {
        return $this->hasMany(ExamSubmission::class, 'student_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function rankings()
    {
        return $this->hasMany(StudentRanking::class, 'student_id');
    }

    // Forum relationships
    public function forumThreads()
    {
        return $this->hasMany(ForumThread::class, 'created_by');
    }

    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class, 'created_by');
    }

    // Activity logs
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
