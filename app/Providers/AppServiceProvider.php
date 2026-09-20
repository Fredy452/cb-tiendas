<?php

namespace App\Providers;

use App\Models\Store;
use App\Observers\StoreObserver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Store::observe(StoreObserver::class);

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Paginator::defaultView('partials.pagination.default');
        Paginator::defaultSimpleView('partials.pagination.simple');

        RateLimiter::for('store-ratings', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });
    }
}
