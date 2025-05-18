<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

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
    public function boot()
    {
        // Force HTTPS in production and local environments
        if ($this->app->environment('production') || $this->app->environment('local')) {
            URL::forceScheme('https');
        }

        // Trust proxies for proper IP detection
        $this->app->bind(
            \Illuminate\Http\Request::class,
            function ($app) {
                $request = Request::createFrom($app['request']);

                // Trust all proxies (or specify Render's IP ranges if known)
                $request->setTrustedProxies(
                    [$request->server->get('REMOTE_ADDR')], // Trust the immediate connection
                    Request::HEADER_X_FORWARDED_FOR |
                    Request::HEADER_X_FORWARDED_HOST |
                    Request::HEADER_X_FORWARDED_PORT |
                    Request::HEADER_X_FORWARDED_PROTO |
                    Request::HEADER_X_FORWARDED_AWS_ELB
                );

                return $request;
            }
        );
    }
}
