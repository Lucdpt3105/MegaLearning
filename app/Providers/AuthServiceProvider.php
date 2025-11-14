<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\ForumQuestion;
use App\Policies\ForumQuestionPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        ForumQuestion::class => ForumQuestionPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
