<?php

namespace App\Http\Middleware;

use Mini\Foundation\Request;
use Mini\Interfaces\Middleware\MiddlewareContract;

class Attribute implements MiddlewareContract
{
    public function handle(Request $request, \Closure $next)
    {
        return $request->get('attribute') ? $next($request) : '属性验证未通过';
    }
}