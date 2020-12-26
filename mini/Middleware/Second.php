<?php

namespace Mini\Middleware;

use Mini\Foundation\Request;
use Mini\Interfaces\Middleware\MiddlewareContrack;

class Second implements MiddlewareContrack
{
    public function handle(Request $request, \Closure $next)
    {
        echo 'second middleware'.PHP_EOL;
        return $next($request);
    }
}