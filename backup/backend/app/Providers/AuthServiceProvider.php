<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Define your model => policy mappings here if needed
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Define your gates here
        Gate::define('approve-requests', function ($user) {
            return $user->role === 'supervisor';
        });
          Gate::define('get-pending-requests', function ($user) {
            return $user->role === 'supervisor';
        });
    }
}