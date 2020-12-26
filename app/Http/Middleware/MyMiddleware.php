<?php

namespace App\Http\Middleware;

use Mini\Foundation\Request;
use Mini\Interfaces\Middleware\MiddlewareContrack;

class MyMiddleware implements MiddlewareContrack
{
    public function handle(Request $request, \Closure $next)
    {
        echo '自定义中间件'.PHP_EOL;
        return $next($request);
    }
}