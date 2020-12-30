<?php

namespace App\Providers;

use Mini\Foundation\ServiceProvider;
use Mini\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
     protected $namespace = 'App\\Http\\Controllers';

    public function register()
    {
        $this->app->singleton('router', Router::class);
    }

    public function boot()
    {
        \Route::namespace($this->namespace)
            ->group(app('path.base').DIRECTORY_SEPARATOR.'routes/api.php');
    }
}