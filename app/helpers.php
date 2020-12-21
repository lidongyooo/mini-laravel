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