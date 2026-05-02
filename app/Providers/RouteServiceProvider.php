<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Booking
        RateLimiter::for('booking', function ($request) {

            $key = $request->user()?->id ?: $request->ip();

            return [
                // limit chính
                Limit::perMinute(5)->by($key),

                // chống spam click nhanh
                Limit::perSecond(10, 2)->by($key),
            ];
        });

        // Search
        RateLimiter::for('search', function ($request) {

            return Limit::perMinute(10)
                ->by($request->ip());
        });

        // Auth
        RateLimiter::for('auth', function ($request) {

            return Limit::perMinute(5)
                ->by($request->ip());
        });
    }
}
