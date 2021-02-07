<?php

namespace Frontier;

use Illuminate\Support\ServiceProvider;

class RouterServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/frontier-router.php', 'frontier-router'
        );

        $this->app->singleton(Router::class, function ($app) {
            return new Router(config('frontier-router'));
        });
    }
}
