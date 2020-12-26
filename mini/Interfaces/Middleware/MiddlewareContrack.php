<?php

namespace Mini\Interfaces\Middleware;

use Mini\Foundation\Request;

interface MiddlewareContrack
{
    public function handle(Request $request, \Closure $next);
}