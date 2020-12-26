<?php
namespace App\Http;

use App\Http\Middleware\MyMiddleware;
use Mini\Foundation\Kernel as HttpKernel;
use Mini\Middleware\TrimStringsAndConvertEmptyStringsToNull;
use Mini\Middleware\ValidatePostSize;

class Kernel extends HttpKernel
{

    protected $middleware = [
        ValidatePostSize::class,
        TrimStringsAndConvertEmptyStringsToNull::class,
        MyMiddleware::class
    ];

}