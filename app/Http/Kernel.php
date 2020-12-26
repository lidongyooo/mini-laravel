<?php
namespace App\Http;

use Mini\Foundation\Kernel as HttpKernel;
use Mini\Middleware\First;
use Mini\Middleware\Second;
use Mini\Middleware\TrimStringsAndConvertEmptyStringsToNull;
use Mini\Middleware\ValidatePostSize;

class Kernel extends HttpKernel
{

    protected $middleware = [
        First::class,
        Second::class,
        ValidatePostSize::class,
        TrimStringsAndConvertEmptyStringsToNull::class
    ];

}