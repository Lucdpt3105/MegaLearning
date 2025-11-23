<?php

namespace App\Policies;

use App\Models\Subject;
use App\Models\User;

class SubjectPolicy
{
    /**
     * Determine if the user can view the subject.
     */
    public function view(User $user, Subject $subject): bool
    {
        // Teacher can view their own subjects
        if ($user->hasRole('teacher') && $subject->teacher_id === $user->id) {
            return true;
        }

        // Admin can view all subjects
        if ($user->hasRole('admin')) {
            return true;
        }

        // Students can view active subjects they're enrolled in
        if ($user->hasRole('student')) {
            return $subject->status === 'active' && 
                   $subject->classRooms()
                       ->whereHas('enrollments', function($query) use ($user) {
                           $query->where('student_id', $user->id);
                       })
                       ->exists();
        }

        return false;
    }

    /**
     * Determine if the user can create subjects.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('teacher') || $user->hasRole('admin');
    }

    /**
     * Determine if the user can update the subject.
     */
    public function update(User $user, Subject $subject): bool
    {
        // Teacher can update their own subjects
        if ($user->hasRole('teacher') && $subject->teacher_id === $user->id) {
            return true;
        }

        // Admin can update all subjects
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the subject.
     */
    public function delete(User $user, Subject $subject): bool
    {
        // Only allow deletion if teacher owns the subject
        if ($user->hasRole('teacher') && $subject->teacher_id === $user->id) {
            return true;
        }

        // Admin can delete all subjects
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }
}
