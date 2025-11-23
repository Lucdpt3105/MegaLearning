<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    /**
     * Determine whether the user can view any documents.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('teacher') || $user->hasRole('student') || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the document.
     */
    public function view(User $user, Document $document): bool
    {
        // Admin can view all
        if ($user->hasRole('admin')) {
            return true;
        }

        // Teacher can view own documents
        if ($user->hasRole('teacher') && $document->uploaded_by === $user->id) {
            return true;
        }

        // Student can view approved documents in their enrolled subjects
        if ($user->hasRole('student') && $document->approval_status === 'approved') {
            // Check if student is enrolled in the subject
            return $user->classEnrollments()
                ->whereHas('classRoom', function($query) use ($document) {
                    $query->where('subject_id', $document->subject_id);
                })
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create documents.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('teacher');
    }

    /**
     * Determine whether the user can update the document.
     */
    public function update(User $user, Document $document): bool
    {
        // Admin can update all
        if ($user->hasRole('admin')) {
            return true;
        }

        // Teacher can only update own documents
        return $user->hasRole('teacher') && $document->uploaded_by === $user->id;
    }

    /**
     * Determine whether the user can delete the document.
     */
    public function delete(User $user, Document $document): bool
    {
        // Admin can delete all
        if ($user->hasRole('admin')) {
            return true;
        }

        // Teacher can only delete own documents
        return $user->hasRole('teacher') && $document->uploaded_by === $user->id;
    }

    /**
     * Determine whether the user can approve the document.
     */
    public function approve(User $user): bool
    {
        return $user->hasRole('admin');
    }
}
