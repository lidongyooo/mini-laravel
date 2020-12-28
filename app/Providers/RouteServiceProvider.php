<?php

namespace App\Providers;

use Mini\Foundation\ServiceProvider;
use Mini\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('router', Router::class);
    }

    public function boot()
    {

    }
}