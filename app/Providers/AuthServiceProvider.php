<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Product;
use App\Models\User;
use App\Policies\AdminPolicy;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => AdminPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('isAdmin', function (User $user) {
            return $user->role_id === 1;
        });

        Gate::define('trueToken', function (User $user, $user_id) {
            return $user->id == $user_id;
        });
    }
}
