<?php

namespace Mini\Middleware;

use Mini\Foundation\Request;
use Mini\Interfaces\Middleware\MiddlewareContract;

class TrimStringsAndConvertEmptyStringsToNull implements MiddlewareContract
{
    protected $except = [
        'password',
        'password_confirmation'
    ];

    public function handle(Request $request, \Closure $next)
    {
        $this->clean($request->get);
        $this->clean($request->post);

        if ($request->isJson()) {
            $request->jsonBootstrap();
            $this->clean($request->json);
        }

        return $next($request);
    }

    protected function clean(&$parameters)
    {
        foreach ($parameters as $key => $value) {
            $parameters[$key] = $this->transform($key, $value);
        }
    }

    protected function transform($key, $value)
    {
        if (in_array($key, $this->except)) {
            return $value;
        }

        $value = is_string($value) ? trim($value): $value;
        $value = $value === '' ? null : $value;

        return $value;
    }
}