<?php

use Mini\Foundation\Container;

if ( !function_exists('app') ) {
    function app($abstract = ''){
        $app = Container::getContainer();
        return $abstract ? $app->make($abstract) : $app;
    }
}

if ( !function_exists('env') ) {
    function env($name, $default = ''){
        return $_ENV[$name] ?? $default;
    }
}

if ( !function_exists('config') ) {
    function config($key, $default = null)
    {
        if (is_null($key)) {
            return app('config');
        }

        return app('config')->get($key, $default);
    }
}


