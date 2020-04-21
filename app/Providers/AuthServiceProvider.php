<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('hasAssigned', function ($user, $task) {
            return $user->id == $task->user_assign_id;
        });
        $gate->define('hasCreated', function ($user, $task) {
            return $user->id == $task->user_id;
        });
        $gate->define('hasCreatedOrAssigned', function ($user, $task) {
            return in_array($user->id,[$task->user_id,$task->user_assign_id]);
        });
    }
}
