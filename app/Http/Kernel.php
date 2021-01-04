<?php
namespace App\Http;

use App\Http\Middleware\Attribute;
use Mini\Foundation\Kernel as HttpKernel;
use App\Http\Middleware\TrimStringsAndConvertEmptyStringsToNull;
use App\Http\Middleware\MyMiddleware;
use Mini\Middleware\ValidatePostSize;

class Kernel extends HttpKernel
{

    protected $middleware = [
        ValidatePostSize::class,
        TrimStringsAndConvertEmptyStringsToNull::class,
        MyMiddleware::class
    ];

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'attribute' => Attribute::class
    ];

}