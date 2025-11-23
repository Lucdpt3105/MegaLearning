<?php

namespace App\Policies;

use App\Models\ForumQuestion;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class ForumQuestionPolicy
{
    use HandlesAuthorization;

    /**
     * Runs before any other policy method.
     * If this returns non-null, that value is used (true = allow, false = deny).
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            // admin được phép mọi hành động
            return true;
        }
        // trả về null để tiếp tục kiểm tra các phương thức bên dưới
        return null;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response|bool
    {
        return $user->id ? Response::allow() : Response::deny('Bạn cần đăng nhập để xem.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ForumQuestion $forumQuestion): Response|bool
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response|bool
    {
        return $user->id ? Response::allow() : Response::deny('Bạn cần đăng nhập để tạo bài.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ForumQuestion $forumQuestion): Response|bool
    {
        return $user->id === $forumQuestion->user_id
            ? Response::allow()
            : Response::deny('Bạn không phải chủ bài này.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ForumQuestion $forumQuestion): Response|bool
    {
         return $user->id === $forumQuestion->user_id
            ? Response::allow()
            : Response::deny('Bạn không được phép xóa bài này.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ForumQuestion $forumQuestion): bool
    {
        return $user->id === $forumQuestion->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ForumQuestion $forumQuestion): bool
    {
        return false;
    }
}
