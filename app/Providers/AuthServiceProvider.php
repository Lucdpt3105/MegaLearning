<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\ForumQuestion;
use App\Policies\ForumQuestionPolicy;
use App\Models\ForumAnswer;
use App\Policies\ForumAnswerPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        ForumQuestion::class => ForumQuestionPolicy::class,
        ForumAnswer::class => ForumAnswerPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
