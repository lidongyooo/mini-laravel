<?php

use Mini\Foundation\container;

if ( !function_exists('app') ) {
    function app($abstract = ''){
        $app = Container::getContainer();
        return $abstract ? $app->make($abstract) : $app;
    }
}