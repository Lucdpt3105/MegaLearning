<?php

namespace App\Policies;

use App\Models\ForumAnswer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ForumAnswerPolicy
{
    use HandlesAuthorization;

    // Admin has all permissions
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): Response|bool
    {
        return $user->id ? Response::allow() : Response::deny('Bạn cần đăng nhập.');
    }

    public function view(User $user, ForumAnswer $forumAnswer): Response|bool
    {
        return Response::allow();
    }

    public function create(User $user): Response|bool
    {
        // any authenticated user (teacher/student) can create answers
        return $user->id ? Response::allow() : Response::deny('Bạn cần đăng nhập.');
    }

    public function update(User $user, ForumAnswer $forumAnswer): Response|bool
    {
        return $user->id === $forumAnswer->user_id
            ? Response::allow()
            : Response::deny('Bạn chỉ sửa được câu trả lời của mình.');
    }

    public function delete(User $user, ForumAnswer $forumAnswer): Response|bool
    {
        return $user->id === $forumAnswer->user_id
            ? Response::allow()
            : Response::deny('Bạn chỉ xóa được câu trả lời của mình.');
    }

    public function restore(User $user, ForumAnswer $forumAnswer): bool
    {
        return $user->id === $forumAnswer->user_id;
    }

    public function forceDelete(User $user, ForumAnswer $forumAnswer): bool
    {
        return false;
    }
}
