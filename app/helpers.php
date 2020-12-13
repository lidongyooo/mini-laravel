<?php

use Mini\Container;

if ( !function_exists('app') ) {
    function app($abstract = ''){
        $app = Container::getInstance();
        return $abstract ? $app->make($abstract) : $app;
    }
}