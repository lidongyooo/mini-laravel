<?php

namespace Mini\Middleware;

use Mini\Foundation\Request;
use Mini\Interfaces\Middleware\MiddlewareContrack;

class First implements MiddlewareContrack
{
    public function handle(Request $request, \Closure $next)
    {
        echo 'first middleware'.PHP_EOL;
        $next($request);
    }
}