<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // You can register any application services here if needed
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Define how the application should authenticate users via the 'api' guard
        $this->app['auth']->viaRequest('api', function ($request) {
            // Check if the request has an 'api_token' parameter
            if ($request->input('api_token')) {
                // Return the user that matches the given 'api_token'
                return User::where('api_token', $request->input('api_token'))->first();
            }
        });
    }
}

