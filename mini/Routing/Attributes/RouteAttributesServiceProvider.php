<?php

namespace Mini\Routing\Attributes;

use Mini\Foundation\ServiceProvider;

class RouteAttributesServiceProvider extends ServiceProvider
{

    public function boot()
    {
        if (!config('route-attributes.enable')) {
            return;
        }

        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        $routeRegistrar = new RouteRegistrar($this->app->make('router'), 'App\\Http\\Controllers');

        foreach (config('route-attributes.directories', []) as $directory) {
            $routeRegistrar->registerDirectory($directory);
        }

    }

}