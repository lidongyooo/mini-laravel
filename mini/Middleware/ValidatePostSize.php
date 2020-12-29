<?php

namespace Mini\Middleware;

use Mini\Exceptions\Middleware\PostTooLargeException;
use Mini\Foundation\Request;
use Mini\Interfaces\Middleware\MiddlewareContract;

class ValidatePostSize implements MiddlewareContract
{

    public function handle(Request $request, \Closure $next)
    {
        $max = $this->getPostMaxSize();

        if ($max > 0 && $request->server('CONTENT_LENGTH') > $max) {
            throw new PostTooLargeException;
        }

        return $next($request);
    }

    protected function getPostMaxSize()
    {
        if (is_numeric($postMaxSize = ini_get('post_max_size'))) {
            return (int)$postMaxSize;
        }

        $metric = strtoupper(substr($postMaxSize, -1));
        $postMaxSize = (int)$postMaxSize;
        switch ($metric) {
            case 'K':
                return $postMaxSize * 1024;
            case 'M':
                return $postMaxSize * 1048576;
            case 'G':
                return $postMaxSize * 1073741824;
            default:
                return $postMaxSize;
        }
    }
}