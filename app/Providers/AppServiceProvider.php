<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Force HTTPS in production
        |--------------------------------------------------------------------------
        */

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        /*
        |--------------------------------------------------------------------------
        | General API Rate Limiter
        |--------------------------------------------------------------------------
        */

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)
                ->by($request->ip());
        });

        /*
        |--------------------------------------------------------------------------
        | Strict Interaction Rate Limiter
        |--------------------------------------------------------------------------
        */

        RateLimiter::for('interactions', function (Request $request) {
            return Limit::perMinute(10)
                ->by($request->ip());
        });

        /*
        |--------------------------------------------------------------------------
        | View Counter Rate Limiter
        |--------------------------------------------------------------------------
        */

        RateLimiter::for('views', function (Request $request) {
            return Limit::perMinute(30)
                ->by($request->ip());
        });
    }
}