<?php

namespace Mini\Interfaces\Middleware;

use Mini\Foundation\Request;

interface MiddlewareContract
{
    public function handle(Request $request, \Closure $next);
}