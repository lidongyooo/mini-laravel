<?php
namespace App\Http;

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

}