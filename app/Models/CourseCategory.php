<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCategory extends Model
{
    // Dùng bảng subjects hiện có
    protected $table = 'subjects';

    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
        'teacher_id',
        'settings',
    ];

    // Một danh mục (subject) có nhiều lớp học (class_rooms)
    public function courses()
    {
        return $this->hasMany(\App\Models\ClassRoom::class, 'subject_id');
    }
}
